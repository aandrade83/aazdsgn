<?php
/**
 * Minimal Google OAuth2 client for the "web server" flow, using only
 * the standard, documented Google Identity endpoints:
 * https://developers.google.com/identity/protocols/oauth2/web-server
 */

require_once __DIR__ . '/config.php';

function google_reviews_build_auth_url(string $state): string
{
    google_reviews_assert_configured();

    $params = [
        'client_id'              => google_reviews_client_id(),
        'redirect_uri'           => google_reviews_redirect_uri(),
        'response_type'          => 'code',
        'scope'                  => GOOGLE_REVIEWS_OAUTH_SCOPE,
        'access_type'            => 'offline',
        'prompt'                 => 'consent',
        'include_granted_scopes' => 'true',
        'state'                  => $state,
    ];

    return GOOGLE_REVIEWS_OAUTH_AUTH_ENDPOINT . '?' . http_build_query($params);
}

/**
 * Exchanges an authorization code for tokens via Google's token endpoint.
 * Returns the decoded response array (contains access_token, refresh_token,
 * expires_in, etc.) on success, or throws on failure. Never logs the body.
 */
function google_reviews_exchange_code_for_tokens(string $code): array
{
    google_reviews_assert_configured();

    if (!function_exists('curl_init')) {
        throw new RuntimeException('The PHP curl extension is required but not available.');
    }

    $postFields = [
        'code'          => $code,
        'client_id'     => google_reviews_client_id(),
        'client_secret' => google_reviews_client_secret(),
        'redirect_uri'  => google_reviews_redirect_uri(),
        'grant_type'    => 'authorization_code',
    ];

    $ch = curl_init(GOOGLE_REVIEWS_OAUTH_TOKEN_ENDPOINT);
    curl_setopt_array($ch, [
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => http_build_query($postFields),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 15,
        CURLOPT_USERAGENT      => 'AAZDSGN-GoogleReviews-OAuth/1.0',
        CURLOPT_HTTPHEADER     => ['Content-Type: application/x-www-form-urlencoded'],
    ]);

    $responseBody = curl_exec($ch);
    $curlErrno    = curl_errno($ch);
    $curlError    = curl_error($ch);
    $httpCode     = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($curlErrno !== 0) {
        throw new RuntimeException('Token exchange request failed (network error, code ' . $curlErrno . ').');
    }

    $decoded = json_decode((string) $responseBody, true);

    if ($httpCode !== 200 || !is_array($decoded)) {
        // Intentionally do not include $responseBody: it may echo back
        // sensitive request parameters or, on success, tokens.
        throw new RuntimeException('Token exchange failed with HTTP status ' . $httpCode . '.');
    }

    if (empty($decoded['access_token'])) {
        throw new RuntimeException('Token exchange response did not include an access token.');
    }

    return $decoded;
}
