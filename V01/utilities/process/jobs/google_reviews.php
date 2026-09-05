<?php

/**
 * vars.php (and everything downstream of it, including db_connect())
 * resolves .env and other paths from $_SERVER['DOCUMENT_ROOT'], which the
 * web SAPI sets automatically but plain PHP CLI does not. This job runs
 * via CLI (`php .../google_reviews.php`), so DOCUMENT_ROOT must be set
 * explicitly before anything is required — computed from this file's own
 * location rather than changing vars.php's global behavior.
 *
 * This file lives at V01/utilities/process/jobs/google_reviews.php, so the
 * project root (the real document root) is 4 directories up.
 */
if (empty($_SERVER['DOCUMENT_ROOT'])) {
    $_SERVER['DOCUMENT_ROOT'] = dirname(__DIR__, 4);
}

require_once($_SERVER['DOCUMENT_ROOT']."/V01/utilities/includes.php");
require_once($_SERVER['DOCUMENT_ROOT']."/V01/utilities/google-reviews/api-client.php");

/**
 * Google Reviews sync job — read-only against Google, upsert-only against
 * MySQL via the existing legacy framework (_Google_reviews + insert()/
 * update()/get()). No new DB layer, no raw SQL from this file.
 *
 * GET/read only against Google. Never modifies, replies to, or deletes
 * reviews or any Business Profile data. Never persists an access token.
 *
 * Run manually for now:
 *   php V01/utilities/process/jobs/google_reviews.php
 * (or via a browser request while GOOGLE_REVIEWS_SETUP_ENABLED-style access
 * control is not yet defined for this job — see note at bottom before
 * wiring a public/cron trigger).
 */

echo "<pre>";
echo "Google Reviews Sync\n";
echo "-------------------\n";

/**
 * Maps the Google starRating enum (ONE..FIVE) to an integer 1-5.
 * Returns null if the value isn't a recognized enum member (never guesses).
 */
function google_reviews_star_rating_to_int($starRating)
{
    $map = [
        'ONE'   => 1,
        'TWO'   => 2,
        'THREE' => 3,
        'FOUR'  => 4,
        'FIVE'  => 5,
    ];

    return isset($map[$starRating]) ? $map[$starRating] : null;
}

/**
 * Converts a Google RFC3339 timestamp (e.g. "2024-06-01T12:34:56Z") to a
 * MySQL DATETIME string, preserving the real Google timestamp value.
 * Returns null if the input is missing or unparsable (never fabricates a
 * date).
 */
function google_reviews_to_mysql_datetime($rfc3339)
{
    if (empty($rfc3339) || !is_string($rfc3339)) {
        return null;
    }

    $timestamp = strtotime($rfc3339);
    if ($timestamp === false) {
        return null;
    }

    return gmdate('Y-m-d H:i:s', $timestamp);
}

/**
 * Extracts the bare review ID, preferring the API's own "reviewId" field
 * and falling back to parsing it out of "name"
 * (accounts/{a}/locations/{l}/reviews/{reviewId}) if that field is absent.
 */
function google_reviews_extract_review_id(array $review)
{
    if (!empty($review['reviewId']) && is_string($review['reviewId'])) {
        return $review['reviewId'];
    }

    if (!empty($review['name']) && is_string($review['name'])) {
        $parts = explode('/', $review['name']);
        $last  = end($parts);
        if ($last !== false && $last !== '') {
            return $last;
        }
    }

    return null;
}

$fetched  = 0;
$inserted = 0;
$updated  = 0;
$errors   = 0;

try {
    $accessToken = google_reviews_get_access_token();

    // Prefer explicit env configuration (no discovery calls, no ambiguity)
    // if it's been set; otherwise fall back to the same safe auto-detection
    // used during diagnosis: only proceed automatically when exactly one
    //
    // IMPORTANT: GOOGLE_REVIEWS_ACCOUNT_ID/GOOGLE_REVIEWS_LOCATION_ID, if
    // set, must be the FULL resource name Google returns (e.g.
    // "accounts/108229233762028444700"), not a bare ID. If only the
    // account is pinned and the location is left to auto-detect,
    // google_reviews_list_locations() below requires the account value to
    // already carry the "accounts/" prefix — see .env.example.
    // account/location exists, never guess among several.
    $accountResourceName  = trim((string) getenv('GOOGLE_REVIEWS_ACCOUNT_ID'));
    $locationResourceName = trim((string) getenv('GOOGLE_REVIEWS_LOCATION_ID'));

    if ($accountResourceName === '') {
        $accounts = google_reviews_list_accounts($accessToken);
        if (count($accounts) !== 1) {
            throw new RuntimeException(
                'Expected exactly one Google Business Profile account but found ' . count($accounts)
                . '. Set GOOGLE_REVIEWS_ACCOUNT_ID in .env to disambiguate.'
            );
        }
        $accountResourceName = $accounts[0]['name'] ?? '';
    }

    if ($locationResourceName === '') {
        $locations = google_reviews_list_locations($accessToken, $accountResourceName);
        if (count($locations) !== 1) {
            throw new RuntimeException(
                'Expected exactly one location under this account but found ' . count($locations)
                . '. Set GOOGLE_REVIEWS_LOCATION_ID in .env to disambiguate.'
            );
        }
        $locationResourceName = $locations[0]['name'] ?? '';
    }

    $reviews = google_reviews_list_reviews($accessToken, $accountResourceName, $locationResourceName);
    unset($accessToken);

    $fetched = count($reviews);

    $existingByReviewId = get_google_reviews_by_review_id_index();
    $syncedAt = gmdate('Y-m-d H:i:s');

    // Columns updated on sync for both insert and update. show_review,
    // created_at and id are intentionally never included here.
    $syncFields = [
        'review_resource_name',
        'reviewer_display_name',
        'reviewer_photo_url',
        'star_rating',
        'google_create_time',
        'google_update_time',
        'comment_present',
        'raw_comment',
        'comment_original',
        'comment_google_translation',
        'has_google_translation',
        'original_language',
        'review_reply',
        'raw_review_json',
        'last_seen_at',
        'fetched_at',
        'is_active',
    ];

    foreach ($reviews as $review) {
        $reviewId = google_reviews_extract_review_id($review);

        if ($reviewId === null) {
            $errors++;
            error_log('[google-reviews-job] Skipped a review with no reviewId/name.');
            continue;
        }

        $comment       = isset($review['comment']) && is_string($review['comment']) ? $review['comment'] : '';
        $commentPresent = $comment !== '';
        $parsedComment  = google_reviews_parse_comment($comment);

        $reviewReplyJson = isset($review['reviewReply']) && is_array($review['reviewReply'])
            ? json_encode($review['reviewReply'])
            : null;

        $rawReviewJson = json_encode($review);

        $fieldValues = [
            'review_resource_name'        => $review['name'] ?? '',
            'reviewer_display_name'       => $review['reviewer']['displayName'] ?? '',
            'reviewer_photo_url'          => $review['reviewer']['profilePhotoUrl'] ?? '',
            'star_rating'                 => google_reviews_star_rating_to_int($review['starRating'] ?? null),
            'google_create_time'          => google_reviews_to_mysql_datetime($review['createTime'] ?? null),
            'google_update_time'          => google_reviews_to_mysql_datetime($review['updateTime'] ?? null),
            'comment_present'             => $commentPresent ? 1 : 0,
            'raw_comment'                 => $comment,
            'comment_original'            => $parsedComment['comment_original'],
            'comment_google_translation'  => $parsedComment['comment_google_translation'],
            'has_google_translation'      => $parsedComment['has_google_translation'] ? 1 : 0,
            'original_language'           => $parsedComment['original_language'],
            'review_reply'                => $reviewReplyJson,
            'raw_review_json'             => $rawReviewJson,
            'last_seen_at'                => $syncedAt,
            'fetched_at'                  => $syncedAt,
            'is_active'                   => 1,
        ];

        try {
            if (isset($existingByReviewId[$reviewId])) {
                $obj = $existingByReviewId[$reviewId];
                foreach ($fieldValues as $key => $value) {
                    $obj->vars[$key] = $value;
                }
                $obj->update($syncFields);
                $updated++;
            } else {
                $obj = new _Google_reviews();
                $obj->vars['review_id']    = $reviewId;
                $obj->vars['show_review']  = 1;
                foreach ($fieldValues as $key => $value) {
                    $obj->vars[$key] = $value;
                }
                $obj->insert();

                if (empty($obj->vars['id']) || $obj->vars['id'] <= 0) {
                    $errors++;
                    error_log('[google-reviews-job] Insert failed for review_id ' . $reviewId . '.');
                } else {
                    $inserted++;
                }
            }
        } catch (Throwable $e) {
            $errors++;
            error_log('[google-reviews-job] Upsert failed for review_id ' . $reviewId . ' (' . get_class($e) . ')');
        }
    }
} catch (Throwable $e) {
    $errors++;
    error_log('[google-reviews-job] Sync aborted (' . get_class($e) . ')');
    echo "Sync aborted before completion. Check server logs.\n";
}

echo "Fetched: {$fetched}\n";
echo "Inserted: {$inserted}\n";
echo "Updated: {$updated}\n";
echo "Errors: {$errors}\n";
echo "</pre>";

// NOTE: this file lives under V01/utilities/, which the root .htaccess
// already blocks entirely from direct web access
// (RewriteRule ^V01/utilities(/|$) - [F,L]) — same protection as the rest
// of this framework's jobs/handlers. It has no access-control of its own,
// relying on that existing block; run it via CLI/cron, not a public URL.
