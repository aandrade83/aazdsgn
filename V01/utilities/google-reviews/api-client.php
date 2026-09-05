<?php
/**
 * Self-contained read-only client for Google Business Profile APIs.
 *
 * Uses the stored refresh token to mint short-lived access tokens, then
 * calls the official Business Profile REST APIs (accounts, locations,
 * reviews). GET-only. Never persists an access token, never prints or
 * logs a token value.
 *
 * Deliberately does not modify oauth-client.php / token-store.php /
 * config.php — this file only reuses their constants and helpers.
 */

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/token-store.php';

const GOOGLE_REVIEWS_ACCOUNTMGMT_BASE   = 'https://mybusinessaccountmanagement.googleapis.com/v1';
const GOOGLE_REVIEWS_BUSINESSINFO_BASE  = 'https://mybusinessbusinessinformation.googleapis.com/v1';
const GOOGLE_REVIEWS_MYBUSINESS_V4_BASE = 'https://mybusiness.googleapis.com/v4';
const GOOGLE_REVIEWS_API_USER_AGENT     = 'AAZDSGN-GoogleReviews-API/1.0';

/**
 * Thrown when a Business Profile API call fails. Carries only
 * non-sensitive diagnostic details (HTTP status, the request URL — which
 * never contains secrets, only resource IDs and pagination tokens — and
 * Google's own error.status/error.message). Never carries a token or
 * Authorization header. Callers should error_log() these details and show
 * the caller only a generic message.
 */
class GoogleReviewsApiException extends RuntimeException
{
    private string $sanitizedUrl;
    private int $httpStatus;
    private ?string $apiErrorStatus;
    private ?string $apiErrorMessage;

    public function __construct(
        string $message,
        string $sanitizedUrl,
        int $httpStatus,
        ?string $apiErrorStatus = null,
        ?string $apiErrorMessage = null
    ) {
        parent::__construct($message);
        $this->sanitizedUrl    = $sanitizedUrl;
        $this->httpStatus      = $httpStatus;
        $this->apiErrorStatus  = $apiErrorStatus;
        $this->apiErrorMessage = $apiErrorMessage;
    }

    public function getSanitizedUrl(): string
    {
        return $this->sanitizedUrl;
    }

    public function getHttpStatus(): int
    {
        return $this->httpStatus;
    }

    public function getApiErrorStatus(): ?string
    {
        return $this->apiErrorStatus;
    }

    public function getApiErrorMessage(): ?string
    {
        return $this->apiErrorMessage;
    }
}

/**
 * Strips a single leading "accounts/" or "locations/" segment, returning
 * just the bare numeric/opaque ID. If the prefix isn't present, the value
 * is returned unchanged (so a bare ID passed in still works).
 */
function google_reviews_strip_resource_prefix(string $value, string $prefix): string
{
    $value = trim($value);
    $prefixWithSlash = rtrim($prefix, '/') . '/';

    if (str_starts_with($value, $prefixWithSlash)) {
        return substr($value, strlen($prefixWithSlash));
    }

    return $value;
}

/**
 * Builds a safe "accounts/{accountId}/locations/{locationId}" parent from
 * an account resource name/ID and a location resource name/ID, regardless
 * of whether each was passed as a bare ID, a short resource name
 * ("locations/Y"), or an already-full parent ("accounts/X/locations/Y").
 */
function google_reviews_build_location_parent(string $accountResourceName, string $locationResourceName): string
{
    $locationResourceName = trim($locationResourceName);

    // Already a full "accounts/X/locations/Y" parent: normalize both IDs
    // and rebuild, rather than trusting the caller's exact formatting.
    if (preg_match('#^accounts/([^/]+)/locations/([^/]+)$#', $locationResourceName, $matches)) {
        return 'accounts/' . $matches[1] . '/locations/' . $matches[2];
    }

    $accountId  = google_reviews_strip_resource_prefix($accountResourceName, 'accounts');
    $locationId = google_reviews_strip_resource_prefix($locationResourceName, 'locations');

    if ($accountId === '' || $locationId === '') {
        throw new RuntimeException('Could not resolve a valid accounts/{id}/locations/{id} parent.');
    }

    return 'accounts/' . $accountId . '/locations/' . $locationId;
}

/**
 * Exchanges the stored refresh token for a short-lived access token.
 * Returns the access token as a plain string, kept in memory only by the
 * caller — never written to disk, never logged, never echoed.
 */
function google_reviews_get_access_token(): string
{
    google_reviews_assert_configured();

    $stored = google_reviews_read_refresh_token();
    if ($stored === null) {
        throw new RuntimeException('No stored refresh token is available. Run the OAuth setup flow first.');
    }

    if (!function_exists('curl_init')) {
        throw new RuntimeException('The PHP curl extension is required but not available.');
    }

    $postFields = [
        'refresh_token' => $stored['refresh_token'],
        'client_id'     => google_reviews_client_id(),
        'client_secret' => google_reviews_client_secret(),
        'grant_type'    => 'refresh_token',
    ];
    unset($stored);

    $ch = curl_init(GOOGLE_REVIEWS_OAUTH_TOKEN_ENDPOINT);
    curl_setopt_array($ch, [
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => http_build_query($postFields),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 15,
        CURLOPT_USERAGENT      => GOOGLE_REVIEWS_API_USER_AGENT,
        CURLOPT_HTTPHEADER     => ['Content-Type: application/x-www-form-urlencoded'],
    ]);
    unset($postFields);

    $responseBody = curl_exec($ch);
    $curlErrno    = curl_errno($ch);
    $httpCode     = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($curlErrno !== 0) {
        throw new RuntimeException('Access token request failed (network error, code ' . $curlErrno . ').');
    }

    $decoded = json_decode((string) $responseBody, true);
    unset($responseBody);

    if ($httpCode !== 200 || !is_array($decoded) || empty($decoded['access_token']) || !is_string($decoded['access_token'])) {
        throw new RuntimeException('Access token request failed with HTTP status ' . $httpCode . '.');
    }

    $accessToken = $decoded['access_token'];
    unset($decoded);

    return $accessToken;
}

/**
 * Performs an authenticated GET request against a Business Profile API
 * and returns the decoded JSON body as an array. Never logs the
 * Authorization header or the access token.
 */
function google_reviews_http_get(string $url, string $accessToken): array
{
    if (!function_exists('curl_init')) {
        throw new RuntimeException('The PHP curl extension is required but not available.');
    }

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_HTTPGET        => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 20,
        CURLOPT_USERAGENT      => GOOGLE_REVIEWS_API_USER_AGENT,
        CURLOPT_HTTPHEADER     => [
            'Authorization: Bearer ' . $accessToken,
            'Accept: application/json',
        ],
    ]);

    $responseBody = curl_exec($ch);
    $curlErrno    = curl_errno($ch);
    $httpCode     = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // Query params here are only resource IDs and pagination tokens
    // (never secrets), so this is safe to log/display.
    $sanitizedUrl = (string) preg_replace('#([?&]key=)[^&]*#i', '$1[redacted]', $url);

    if ($curlErrno !== 0) {
        throw new GoogleReviewsApiException(
            'Business Profile API request failed (network error, code ' . $curlErrno . ').',
            $sanitizedUrl,
            0
        );
    }

    $decoded = json_decode((string) $responseBody, true);

    if ($httpCode !== 200) {
        $apiErrorStatus  = null;
        $apiErrorMessage = null;
        if (is_array($decoded) && isset($decoded['error']) && is_array($decoded['error'])) {
            $apiErrorStatus  = isset($decoded['error']['status']) && is_string($decoded['error']['status'])
                ? $decoded['error']['status'] : null;
            $apiErrorMessage = isset($decoded['error']['message']) && is_string($decoded['error']['message'])
                ? $decoded['error']['message'] : null;
        }

        throw new GoogleReviewsApiException(
            'Business Profile API request failed with HTTP status ' . $httpCode . '.',
            $sanitizedUrl,
            $httpCode,
            $apiErrorStatus,
            $apiErrorMessage
        );
    }

    if (!is_array($decoded)) {
        throw new GoogleReviewsApiException(
            'Business Profile API returned an unexpected (non-JSON-object) response.',
            $sanitizedUrl,
            $httpCode
        );
    }

    return $decoded;
}

/**
 * Lists all Business Profile accounts accessible to the authorized user.
 * Follows nextPageToken until exhausted.
 *
 * @return array<int, array<string, mixed>> Raw "account" objects from the API.
 */
function google_reviews_list_accounts(string $accessToken): array
{
    $accounts   = [];
    $pageToken  = null;

    do {
        $query = [];
        if ($pageToken !== null) {
            $query['pageToken'] = $pageToken;
        }

        $url = GOOGLE_REVIEWS_ACCOUNTMGMT_BASE . '/accounts'
            . ($query ? ('?' . http_build_query($query)) : '');

        $response = google_reviews_http_get($url, $accessToken);

        foreach ($response['accounts'] ?? [] as $account) {
            $accounts[] = $account;
        }

        $pageToken = $response['nextPageToken'] ?? null;
    } while ($pageToken !== null && $pageToken !== '');

    return $accounts;
}

/**
 * Lists all locations under a given account resource name
 * (e.g. "accounts/123456789", or "accounts/-" to try the wildcard form).
 * Follows nextPageToken until exhausted.
 *
 * @return array<int, array<string, mixed>> Raw "location" objects from the API.
 */
function google_reviews_list_locations(string $accessToken, string $accountResourceName): array
{
    $locations = [];
    $pageToken = null;

    do {
        $query = ['readMask' => 'name,title'];
        if ($pageToken !== null) {
            $query['pageToken'] = $pageToken;
        }

        $url = GOOGLE_REVIEWS_BUSINESSINFO_BASE . '/' . $accountResourceName . '/locations'
            . '?' . http_build_query($query);

        $response = google_reviews_http_get($url, $accessToken);

        foreach ($response['locations'] ?? [] as $location) {
            $locations[] = $location;
        }

        $pageToken = $response['nextPageToken'] ?? null;
    } while ($pageToken !== null && $pageToken !== '');

    return $locations;
}

/**
 * Lists all reviews for a location, given the account resource name/ID and
 * the location resource name/ID (each may be a bare ID, a short resource
 * name like "locations/Y", or an already-full "accounts/X/locations/Y").
 * The two are combined into the full parent required by the v4 reviews API
 * (accounts.list and locations.list return the account and location
 * resource names separately, so they must be joined here, not assumed).
 * Follows nextPageToken until exhausted.
 *
 * @return array<int, array<string, mixed>> Raw "review" objects from the API.
 */
function google_reviews_list_reviews(string $accessToken, string $accountResourceName, string $locationResourceName): array
{
    $parent = google_reviews_build_location_parent($accountResourceName, $locationResourceName);

    $reviews   = [];
    $pageToken = null;

    do {
        $query = ['pageSize' => 50];
        if ($pageToken !== null) {
            $query['pageToken'] = $pageToken;
        }

        $url = GOOGLE_REVIEWS_MYBUSINESS_V4_BASE . '/' . $parent . '/reviews'
            . '?' . http_build_query($query);

        $response = google_reviews_http_get($url, $accessToken);

        foreach ($response['reviews'] ?? [] as $review) {
            $reviews[] = $review;
        }

        $pageToken = $response['nextPageToken'] ?? null;
    } while ($pageToken !== null && $pageToken !== '');

    return $reviews;
}
