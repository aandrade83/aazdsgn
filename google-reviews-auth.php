<?php
/**
 * ONE-TIME SETUP ENDPOINT — Google Business Profile Reviews OAuth.
 *
 * Starts the Google OAuth2 consent flow to obtain a refresh token for the
 * account that manages the AAZDSGN Google Business Profile.
 *
 * This endpoint (and its counterpart google-reviews-oauth-callback.php) is
 * only needed until a valid refresh token has been stored. After that,
 * both files can be safely deleted or disabled.
 *
 * Fail-closed: this endpoint only responds when GOOGLE_REVIEWS_SETUP_ENABLED
 * is exactly "true" (case-insensitive, trimmed). Missing or any other value
 * results in a 404.
 */

require_once __DIR__ . '/V01/utilities/vars.php';
require_once __DIR__ . '/V01/utilities/google-reviews/config.php';
require_once __DIR__ . '/V01/utilities/google-reviews/oauth-client.php';

header('Cache-Control: no-store, no-cache');
header('Pragma: no-cache');
header('Expires: 0');

$setupEnabled = strtolower(trim((string) getenv('GOOGLE_REVIEWS_SETUP_ENABLED')));
if ($setupEnabled !== 'true') {
    http_response_code(404);
    exit('Not found.');
}

try {
    google_reviews_assert_configured();
} catch (Throwable $e) {
    http_response_code(500);
    exit('Google Reviews OAuth is not configured. Check server environment variables.');
}

$state = bin2hex(random_bytes(32));
$_SESSION['google_reviews_oauth_state'] = $state;

$authUrl = google_reviews_build_auth_url($state);

header('Location: ' . $authUrl, true, 302);
exit;
