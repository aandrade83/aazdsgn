<?php
/**
 * Reads/writes the Google refresh token from a private, non-web-accessible
 * JSON file (V01/utilities/google-reviews/private/refresh-token.json).
 *
 * This file only handles storage. It never prints, logs, or throws the
 * token value itself — errors reference the operation, not the secret.
 */

require_once __DIR__ . '/config.php';

/**
 * @return array{refresh_token:string, obtained_at:string}|null
 */
function google_reviews_read_refresh_token(): ?array
{
    if (!is_readable(GOOGLE_REVIEWS_REFRESH_TOKEN_FILE)) {
        return null;
    }

    $raw = file_get_contents(GOOGLE_REVIEWS_REFRESH_TOKEN_FILE);
    if ($raw === false || $raw === '') {
        return null;
    }

    $data = json_decode($raw, true);
    if (!is_array($data) || !isset($data['refresh_token']) || !is_string($data['refresh_token']) || $data['refresh_token'] === '') {
        return null;
    }

    return $data;
}

/**
 * Atomically writes the refresh token to the private storage file,
 * with restrictive permissions where the hosting environment allows it.
 */
function google_reviews_save_refresh_token(string $refreshToken): void
{
    if ($refreshToken === '') {
        throw new RuntimeException('Refused to store an empty refresh token.');
    }

    if (!is_dir(GOOGLE_REVIEWS_PRIVATE_DIR)) {
        if (!mkdir(GOOGLE_REVIEWS_PRIVATE_DIR, 0700, true) && !is_dir(GOOGLE_REVIEWS_PRIVATE_DIR)) {
            throw new RuntimeException('Could not create private storage directory.');
        }
    }
    @chmod(GOOGLE_REVIEWS_PRIVATE_DIR, 0700);

    $payload = json_encode(
        [
            'refresh_token' => $refreshToken,
            'obtained_at'   => gmdate('c'),
        ],
        JSON_UNESCAPED_SLASHES
    );

    if ($payload === false) {
        throw new RuntimeException('Could not encode refresh token payload.');
    }

    // Atomic write: write to a temp file in the same directory, then rename.
    $tmpFile = GOOGLE_REVIEWS_PRIVATE_DIR . '/.refresh-token.tmp-' . bin2hex(random_bytes(8));

    $written = file_put_contents($tmpFile, $payload, LOCK_EX);
    if ($written === false) {
        @unlink($tmpFile);
        throw new RuntimeException('Could not write refresh token temp file.');
    }
    @chmod($tmpFile, 0600);

    if (!rename($tmpFile, GOOGLE_REVIEWS_REFRESH_TOKEN_FILE)) {
        @unlink($tmpFile);
        throw new RuntimeException('Could not finalize refresh token file.');
    }
    @chmod(GOOGLE_REVIEWS_REFRESH_TOKEN_FILE, 0600);
}
