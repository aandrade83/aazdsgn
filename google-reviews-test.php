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
 *   &location=accounts/XXXX/locations/YYYY            -> use this location,
 *                                                         skip straight to
 *                                                         reviews
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

try {
    $accessToken = google_reviews_get_access_token();
} catch (Throwable $e) {
    error_log('[google-reviews-test] Access token request failed (' . get_class($e) . ')');
    google_reviews_test_fail('Could not obtain an access token. Check server logs.');
}

$requestedAccount  = isset($_GET['account']) && is_string($_GET['account']) ? trim($_GET['account']) : '';
$requestedLocation = isset($_GET['location']) && is_string($_GET['location']) ? trim($_GET['location']) : '';

// --- Path 1: a specific location was given -> go straight to reviews ---
if ($requestedLocation !== '') {
    echo "=== Location (from ?location=) ===\n";
    echo $requestedLocation . "\n\n";

    try {
        $reviews = google_reviews_list_reviews($accessToken, $requestedLocation);
    } catch (Throwable $e) {
        error_log('[google-reviews-test] reviews.list failed (' . get_class($e) . ')');
        google_reviews_test_fail('Could not list reviews for this location. Check server logs.');
    }

    echo "=== Reviews ===\n";
    echo 'total_fetched: ' . count($reviews) . "\n\n";

    foreach ($reviews as $i => $review) {
        $displayName = $review['reviewer']['displayName'] ?? '(no name)';
        $starRating  = $review['starRating'] ?? '(none)';
        $createTime  = $review['createTime'] ?? '(none)';
        $updateTime  = $review['updateTime'] ?? '(none)';
        $comment     = $review['comment'] ?? '';

        echo '--- review #' . ($i + 1) . " ---\n";
        echo 'reviewer.displayName: ' . $displayName . "\n";
        echo 'starRating: ' . $starRating . "\n";
        echo 'createTime: ' . $createTime . "\n";
        echo 'updateTime: ' . $updateTime . "\n";
        echo "comment:\n" . $comment . "\n\n";
    }

    exit;
}

// --- Path 2: resolve account, then location(s) ---

try {
    $accounts = google_reviews_list_accounts($accessToken);
} catch (Throwable $e) {
    error_log('[google-reviews-test] accounts.list failed (' . get_class($e) . ')');
    google_reviews_test_fail('Could not list accounts. Check server logs.');
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
    error_log('[google-reviews-test] locations.list failed (' . get_class($e) . ')');
    google_reviews_test_fail('Could not list locations for this account. Check server logs.');
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
    $reviews = google_reviews_list_reviews($accessToken, $locationResourceName);
} catch (Throwable $e) {
    error_log('[google-reviews-test] reviews.list failed (' . get_class($e) . ')');
    google_reviews_test_fail('Could not list reviews for this location. Check server logs.');
}

echo "=== Reviews ===\n";
echo 'total_fetched: ' . count($reviews) . "\n\n";

foreach ($reviews as $i => $review) {
    $displayName = $review['reviewer']['displayName'] ?? '(no name)';
    $starRating  = $review['starRating'] ?? '(none)';
    $createTime  = $review['createTime'] ?? '(none)';
    $updateTime  = $review['updateTime'] ?? '(none)';
    $comment     = $review['comment'] ?? '';

    echo '--- review #' . ($i + 1) . " ---\n";
    echo 'reviewer.displayName: ' . $displayName . "\n";
    echo 'starRating: ' . $starRating . "\n";
    echo 'createTime: ' . $createTime . "\n";
    echo 'updateTime: ' . $updateTime . "\n";
    echo "comment:\n" . $comment . "\n\n";
}
