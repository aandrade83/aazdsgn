<?php
/**
 * Google Business Profile Reviews — OAuth configuration.
 *
 * Reads client credentials from the environment (.env, loaded by
 * V01/utilities/vars.php via getenv()). Nothing here is hardcoded.
 */

if (!defined('AAZ_GOOGLE_REVIEWS_INTERNAL')) {
    define('AAZ_GOOGLE_REVIEWS_INTERNAL', true);
}

const GOOGLE_REVIEWS_OAUTH_AUTH_ENDPOINT  = 'https://accounts.google.com/o/oauth2/v2/auth';
const GOOGLE_REVIEWS_OAUTH_TOKEN_ENDPOINT = 'https://oauth2.googleapis.com/token';
const GOOGLE_REVIEWS_OAUTH_SCOPE          = 'https://www.googleapis.com/auth/business.manage';

const GOOGLE_REVIEWS_PRIVATE_DIR         = __DIR__ . '/private';
const GOOGLE_REVIEWS_REFRESH_TOKEN_FILE  = GOOGLE_REVIEWS_PRIVATE_DIR . '/refresh-token.json';

function google_reviews_client_id(): string
{
    $value = getenv('GOOGLE_REVIEWS_CLIENT_ID');
    return $value !== false ? trim($value) : '';
}

function google_reviews_client_secret(): string
{
    $value = getenv('GOOGLE_REVIEWS_CLIENT_SECRET');
    return $value !== false ? trim($value) : '';
}

function google_reviews_redirect_uri(): string
{
    $value = getenv('GOOGLE_REVIEWS_REDIRECT_URI');
    return $value !== false ? trim($value) : '';
}

/**
 * Fails loudly (without leaking secret values) if required env vars are missing.
 */
function google_reviews_assert_configured(): void
{
    $missing = [];

    if (google_reviews_client_id() === '') {
        $missing[] = 'GOOGLE_REVIEWS_CLIENT_ID';
    }
    if (google_reviews_client_secret() === '') {
        $missing[] = 'GOOGLE_REVIEWS_CLIENT_SECRET';
    }
    if (google_reviews_redirect_uri() === '') {
        $missing[] = 'GOOGLE_REVIEWS_REDIRECT_URI';
    }

    if (!empty($missing)) {
        throw new RuntimeException(
            'Missing required Google Reviews environment variables: ' . implode(', ', $missing)
        );
    }
}
