<?php
/**
 * ONE-TIME SETUP ENDPOINT — Google Business Profile Reviews OAuth callback.
 *
 * Registered redirect URI in Google Cloud Console:
 * https://www.aazdsgn.com/google-reviews-oauth-callback.php
 *
 * Validates the OAuth state, exchanges the authorization code for tokens,
 * and stores only the refresh token in private, non-web-accessible storage.
 * Never displays or logs token values.
 *
 * Like google-reviews-auth.php, this file is only needed for initial setup
 * and can be deleted/disabled afterwards.
 *
 * Fail-closed: this endpoint only responds when GOOGLE_REVIEWS_SETUP_ENABLED
 * is exactly "true" (case-insensitive, trimmed). Missing or any other value
 * results in a 404.
 */

require_once __DIR__ . '/V01/utilities/vars.php';
require_once __DIR__ . '/V01/utilities/google-reviews/config.php';
require_once __DIR__ . '/V01/utilities/google-reviews/oauth-client.php';
require_once __DIR__ . '/V01/utilities/google-reviews/token-store.php';

header('Cache-Control: no-store, no-cache');
header('Pragma: no-cache');
header('Expires: 0');

$setupEnabled = strtolower(trim((string) getenv('GOOGLE_REVIEWS_SETUP_ENABLED')));
if ($setupEnabled !== 'true') {
    http_response_code(404);
    exit('Not found.');
}

function google_reviews_fail(string $message): void
{
    http_response_code(400);
    echo '<p>' . htmlspecialchars($message, ENT_QUOTES) . '</p>';
    exit;
}

if (!empty($_GET['error'])) {
    google_reviews_fail('Google returned an error and no authorization was granted. You can close this page and try again.');
}

$receivedState = $_GET['state'] ?? '';
$expectedState = $_SESSION['google_reviews_oauth_state'] ?? null;
unset($_SESSION['google_reviews_oauth_state']);

if (
    $expectedState === null
    || $receivedState === ''
    || !hash_equals((string) $expectedState, (string) $receivedState)
) {
    google_reviews_fail('Invalid or expired authorization request (state mismatch). Please restart the authorization from google-reviews-auth.php.');
}

$code = $_GET['code'] ?? '';
if ($code === '') {
    google_reviews_fail('No authorization code was received from Google.');
}

try {
    $tokens = google_reviews_exchange_code_for_tokens($code);
} catch (Throwable $e) {
    error_log('[google-reviews-oauth] Token exchange failed (' . get_class($e) . ')');
    google_reviews_fail('Could not complete authorization with Google. Please try again.');
}

if (empty($tokens['refresh_token'])) {
    // Happens if this Google account already granted consent before and
    // Google omitted a new refresh_token. Revoke prior access at
    // https://myaccount.google.com/permissions and retry with prompt=consent
    // (already set), or clear any existing stored token first.
    google_reviews_fail(
        'Authorization succeeded but Google did not return a refresh token. '
        . 'This usually means this account already has an active grant. '
        . 'Revoke prior access for this app at myaccount.google.com/permissions and try again.'
    );
}

try {
    google_reviews_save_refresh_token($tokens['refresh_token']);
} catch (Throwable $e) {
    error_log('[google-reviews-oauth] Failed to persist refresh token (' . get_class($e) . ')');
    google_reviews_fail('Authorization succeeded but the refresh token could not be saved. Check server logs.');
}

// Token values are intentionally never included below.
unset($tokens, $code);

http_response_code(200);
?><!DOCTYPE html>
<html lang="en">
<head><meta charset="utf-8"><title>Google Reviews — Authorization complete</title></head>
<body>
    <h1>Authorization complete</h1>
    <p>The Google Business Profile account was successfully authorized. You can now close this page.</p>
    <p>For security, you should now disable or delete <code>google-reviews-auth.php</code> and <code>google-reviews-oauth-callback.php</code> (or set <code>GOOGLE_REVIEWS_SETUP_ENABLED=false</code> in <code>.env</code>).</p>
</body>
</html>
