<?php
/**
 * TEMPORARY DIAGNOSTIC ENDPOINT — Google Business Profile API read test.
 *
 * Read-only (GET/list calls only). Never modifies, replies to, or deletes
 * reviews or any Business Profile data. Does not write to MySQL.
 *
 * Protected independently of GOOGLE_REVIEWS_SETUP_ENABLED via the same
 * GOOGLE_REVIEWS_DIAG_KEY used by google-reviews-env-check.php.
 *
 * Usage:
 *   ?key=<diag key>                                   -> lists accounts (and
 *                                                         locations, if a
 *                                                         single account was
 *                                                         auto-selected)
 *   &account=accounts/XXXX                            -> use this account
 *   &location=accounts/XXXX/locations/YYYY            -> use this location
 *   &location=locations/YYYY (with &account=accounts/XXXX) -> also accepted
 *                                                         both skip straight
 *                                                         to reviews
 *
 * DELETE THIS FILE once Phase 2 is validated.
 */

require_once __DIR__ . '/V01/utilities/vars.php';
require_once __DIR__ . '/V01/utilities/google-reviews/api-client.php';

header('Cache-Control: no-store');
header('Pragma: no-cache');
header('Expires: 0');
header('Content-Type: text/plain; charset=utf-8');

$diagKey     = getenv('GOOGLE_REVIEWS_DIAG_KEY');
$suppliedKey = $_GET['key'] ?? '';

if (
    $diagKey === false
    || $diagKey === ''
    || !is_string($suppliedKey)
    || $suppliedKey === ''
    || !hash_equals($diagKey, $suppliedKey)
) {
    http_response_code(404);
    exit('Not found.');
}
unset($diagKey, $suppliedKey);

function google_reviews_test_fail(string $message): void
{
    http_response_code(500);
    echo "ERROR: {$message}\n";
    exit;
}

/**
 * Logs safe diagnostic details for a failed Business Profile API call
 * (HTTP status, sanitized URL, Google's error.status/error.message) and
 * always shows the caller only a generic message — never Google's raw
 * error text, and never any token/Authorization header.
 */
function google_reviews_test_log_and_fail(string $context, Throwable $e): void
{
    if ($e instanceof GoogleReviewsApiException) {
        error_log(sprintf(
            '[google-reviews-test] %s failed | http_status=%d | url=%s | api_error_status=%s | api_error_message=%s',
            $context,
            $e->getHttpStatus(),
            $e->getSanitizedUrl(),
            $e->getApiErrorStatus() ?? '(none)',
            $e->getApiErrorMessage() ?? '(none)'
        ));
    } else {
        error_log('[google-reviews-test] ' . $context . ' failed (' . get_class($e) . ')');
    }

    if ($e instanceof GoogleReviewsApiException) {
        http_response_code(500);
        echo "ERROR calling Google Business Profile " . ucfirst($context) . " API\n";
        echo 'HTTP status: ' . $e->getHttpStatus() . "\n";
        echo 'URL: ' . $e->getSanitizedUrl() . "\n";
        echo 'Google status: ' . ($e->getApiErrorStatus() ?? '(none)') . "\n";
        echo 'Google message: ' . ($e->getApiErrorMessage() ?? '(none)') . "\n";
        exit;
    }

    google_reviews_test_fail('Could not list ' . $context . '. Check server logs.');
}

try {
    $accessToken = google_reviews_get_access_token();
} catch (Throwable $e) {
    error_log('[google-reviews-test] Access token request failed (' . get_class($e) . ')');
    google_reviews_test_fail('Could not obtain an access token. Check server logs.');
}

$requestedAccount  = isset($_GET['account']) && is_string($_GET['account']) ? trim($_GET['account']) : '';
$requestedLocation = isset($_GET['location']) && is_string($_GET['location']) ? trim($_GET['location']) : '';

/**
 * Prints only non-sensitive review metadata and per-review fields.
 * Never prints tokens, headers, or the raw comment translation marker
 * parsing result beyond what was explicitly requested for this diagnostic.
 *
 * @param array<int, array<string, mixed>> $reviews
 * @param array{averageRating?: mixed, totalReviewCount?: mixed, first_page_had_next_page_token?: bool}|null $meta
 */
function google_reviews_test_print_reviews(array $reviews, ?array $meta): void
{
    echo "=== Reviews — response metadata (first page) ===\n";
    echo 'totalReviewCount: ' . (isset($meta['totalReviewCount']) && $meta['totalReviewCount'] !== null ? $meta['totalReviewCount'] : '(not present in response)') . "\n";
    echo 'averageRating: ' . (isset($meta['averageRating']) && $meta['averageRating'] !== null ? $meta['averageRating'] : '(not present in response)') . "\n";
    echo 'nextPageToken present on first page: ' . (!empty($meta['first_page_had_next_page_token']) ? 'yes' : 'no') . "\n";
    echo 'reviews fetched after following all pages: ' . count($reviews) . "\n\n";

    foreach ($reviews as $i => $review) {
        $resourceName = $review['name'] ?? ($review['reviewId'] ?? '(none)');
        $displayName  = $review['reviewer']['displayName'] ?? '(no name)';
        $starRating   = $review['starRating'] ?? '(none)';
        $createTime   = $review['createTime'] ?? '(none)';
        $updateTime   = $review['updateTime'] ?? '(none)';
        $hasComment   = isset($review['comment']) && $review['comment'] !== '';

        echo '--- review #' . ($i + 1) . " ---\n";
        echo 'name/reviewId: ' . $resourceName . "\n";
        echo 'reviewer.displayName: ' . $displayName . "\n";
        echo 'starRating: ' . $starRating . "\n";
        echo 'createTime: ' . $createTime . "\n";
        echo 'updateTime: ' . $updateTime . "\n";
        echo 'comment_present: ' . ($hasComment ? 'yes' : 'no') . "\n";

        if ($hasComment) {
            $parsed = google_reviews_parse_comment($review['comment']);
            echo 'has_google_translation: ' . ($parsed['has_google_translation'] ? 'yes' : 'no') . "\n";
            echo 'original_language: ' . ($parsed['original_language'] ?? '(null — not guessed)') . "\n";
            echo "comment_original:\n" . $parsed['comment_original'] . "\n";
            if ($parsed['has_google_translation']) {
                echo "comment_google_translation:\n" . $parsed['comment_google_translation'] . "\n";
            }
        }
        echo "\n";
    }
}

// --- Path 1: a specific location was given -> go straight to reviews ---
if ($requestedLocation !== '') {
    echo "=== Location (from ?location=) ===\n";
    echo $requestedLocation . "\n";
    if ($requestedAccount !== '') {
        echo 'account (from ?account=): ' . $requestedAccount . "\n";
    }
    echo "\n";

    try {
        // If $requestedLocation is already a full "accounts/X/locations/Y"
        // parent, google_reviews_build_location_parent() uses it as-is and
        // $requestedAccount (possibly empty) is ignored; otherwise both are
        // required to build the parent.
        $reviewsMeta = null;
        $reviews = google_reviews_list_reviews($accessToken, $requestedAccount, $requestedLocation, $reviewsMeta);
    } catch (Throwable $e) {
        google_reviews_test_log_and_fail('reviews', $e);
    }

    google_reviews_test_print_reviews($reviews, $reviewsMeta);
    exit;
}

// --- Path 2: resolve account, then location(s) ---

try {
    $accounts = google_reviews_list_accounts($accessToken);
} catch (Throwable $e) {
    google_reviews_test_log_and_fail('accounts', $e);
}

if (empty($accounts)) {
    google_reviews_test_fail('No accounts were returned for the authorized user.');
}

$selectedAccount = null;

if ($requestedAccount !== '') {
    foreach ($accounts as $account) {
        if (($account['name'] ?? '') === $requestedAccount) {
            $selectedAccount = $account;
            break;
        }
    }
    if ($selectedAccount === null) {
        google_reviews_test_fail('The requested ?account= was not found among the accessible accounts.');
    }
} elseif (count($accounts) === 1) {
    $selectedAccount = $accounts[0];
} else {
    echo "=== Multiple accounts found — pass ?account=<name> to pick one ===\n\n";
    foreach ($accounts as $account) {
        echo 'name: ' . ($account['name'] ?? '(unknown)') . "\n";
        echo 'accountName: ' . ($account['accountName'] ?? '(unknown)') . "\n";
        echo 'type: ' . ($account['type'] ?? '(unknown)') . "\n\n";
    }
    exit;
}

echo "=== Selected account ===\n";
echo 'name: ' . ($selectedAccount['name'] ?? '(unknown)') . "\n";
echo 'accountName: ' . ($selectedAccount['accountName'] ?? '(unknown)') . "\n";
echo 'type: ' . ($selectedAccount['type'] ?? '(unknown)') . "\n\n";

$accountResourceName = $selectedAccount['name'] ?? '';
unset($selectedAccount, $accounts);

try {
    $locations = google_reviews_list_locations($accessToken, $accountResourceName);
} catch (Throwable $e) {
    google_reviews_test_log_and_fail('locations', $e);
}

if (empty($locations)) {
    echo "No locations were returned for accounts/{id}/locations.\n";
    echo "If you expected results, you can try the wildcard form manually:\n";
    echo "  ?account=accounts/-\n";
    exit;
}

if (count($locations) > 1) {
    echo "=== Multiple locations found — pass &location=<name> to pick one ===\n\n";
    foreach ($locations as $location) {
        echo 'name: ' . ($location['name'] ?? '(unknown)') . "\n";
        echo 'title: ' . ($location['title'] ?? '(unknown)') . "\n\n";
    }
    exit;
}

// Exactly one location: auto-select and fetch its reviews.
$location = $locations[0];
$locationResourceName = $location['name'] ?? '';

echo "=== Auto-selected location (only one found) ===\n";
echo 'name: ' . $locationResourceName . "\n";
echo 'title: ' . ($location['title'] ?? '(unknown)') . "\n\n";
unset($location, $locations);

try {
    $reviewsMeta = null;
    $reviews = google_reviews_list_reviews($accessToken, $accountResourceName, $locationResourceName, $reviewsMeta);
} catch (Throwable $e) {
    google_reviews_test_log_and_fail('reviews', $e);
}

google_reviews_test_print_reviews($reviews, $reviewsMeta);
