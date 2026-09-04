<?php 
require_once $_SERVER['DOCUMENT_ROOT'] . '/V01/utilities/vars.php';
header('Content-Type: application/json; charset=utf-8');

function respond($success, $message, $httpCode = 200) {
    http_response_code($httpCode);
    echo json_encode(['success' => $success, 'message' => $message]);
    exit;
}

function spamMessage($lang) {
    return $lang === '_en'
        ? 'We could not send your message. Please try again in a few minutes.'
        : 'No pudimos enviar el mensaje. Inténtelo nuevamente en unos minutos.';
}

function getClientIp() {
    $ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $forwarded = trim(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0]);
        if (filter_var($forwarded, FILTER_VALIDATE_IP)) {
            $ip = $forwarded;
        }
    } elseif (!empty($_SERVER['HTTP_CLIENT_IP']) && filter_var($_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP)) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    }
    return $ip;
}

function logSpamAttempt($ip, $reason) {
    $logFile = $_SERVER['DOCUMENT_ROOT'] . '/V01/utilities/spam_log.txt';
    $ua = isset($_SERVER['HTTP_USER_AGENT']) ? substr($_SERVER['HTTP_USER_AGENT'], 0, 255) : '';
    $line = sprintf("[%s] ip=%s reason=%s ua=%s\n", date('Y-m-d H:i:s'), $ip, $reason, $ua);
    @file_put_contents($logFile, $line, FILE_APPEND | LOCK_EX);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    respond(false, 'Method not allowed.', 405);
}

$lang = (isset($_POST['lang']) && $_POST['lang'] === '_en') ? '_en' : '_esp';
$ip   = getClientIp();
$now  = time();

// Rate limit: at most one submission per IP every 60 seconds.
$rateLimitDir    = $_SERVER['DOCUMENT_ROOT'] . '/V01/utilities/cache/ratelimit';
$rateLimitWindow = 60;
$rateLimited     = false;
if (!is_dir($rateLimitDir)) {
    @mkdir($rateLimitDir, 0755, true);
}
if (is_dir($rateLimitDir) && is_writable($rateLimitDir)) {
    $rateLimitFile = $rateLimitDir . '/' . md5($ip) . '.txt';
    if (is_file($rateLimitFile)) {
        $lastSubmit = (int) @file_get_contents($rateLimitFile);
        if ($lastSubmit && ($now - $lastSubmit) < $rateLimitWindow) {
            $rateLimited = true;
        }
    }
    if (!$rateLimited) {
        @file_put_contents($rateLimitFile, (string) $now, LOCK_EX);
    }
}
if ($rateLimited) {
    logSpamAttempt($ip, 'rate_limited');
    respond(false, spamMessage($lang), 429);
}

// Honeypot: real visitors never see or fill this field. Pretend success so bots don't retry.
$honeypot = isset($_POST['company']) ? trim($_POST['company']) : '';
if ($honeypot !== '') {
    logSpamAttempt($ip, 'honeypot');
    respond(true, 'Message sent successfully.');
}

// Minimum submission time: blocks bots that fill and submit the form instantly.
$formTs     = isset($_POST['ts']) ? (int) $_POST['ts'] : 0;
$minSeconds = 3;
if ($formTs <= 0 || ($now - $formTs) < $minSeconds) {
    logSpamAttempt($ip, 'too_fast');
    respond(false, spamMessage($lang), 422);
}

$name    = isset($_POST['name']) ? trim($_POST['name']) : '';
$email   = isset($_POST['email']) ? trim($_POST['email']) : '';
$subject = isset($_POST['subject']) ? trim($_POST['subject']) : '';
$message = isset($_POST['message']) ? trim($_POST['message']) : '';

if (strlen($name) > 100 || strlen($email) > 150 || strlen($subject) > 150 || strlen($message) > 5000) {
    logSpamAttempt($ip, 'field_too_long');
    respond(false, spamMessage($lang), 422);
}

if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    respond(false, spamMessage($lang), 422);
}

if ($message === '') {
    respond(false, spamMessage($lang), 422);
}

// Spam keyword / URL check: reject messages with more than 2 links.
$urlCount = preg_match_all('#\bhttps?://\S+|\bwww\.\S+#i', $message);
if ($urlCount > 2) {
    logSpamAttempt($ip, 'too_many_urls');
    respond(false, spamMessage($lang), 422);
}

$apiKey      = getenv('RESEND_API_KEY');
$contactTo   = getenv('CONTACT_TO');
$contactCc   = getenv('CONTACT_CC');
$contactFrom = getenv('CONTACT_FROM');

if (!$apiKey || !$contactTo || !$contactFrom) {
    error_log('mail.php: missing RESEND_API_KEY/CONTACT_TO/CONTACT_FROM environment variables.');
    respond(false, spamMessage($lang), 500);
}

$safeName    = htmlspecialchars($name !== '' ? $name : 'N/A', ENT_QUOTES, 'UTF-8');
$safeEmail   = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
$safeSubject = htmlspecialchars($subject !== '' ? $subject : 'N/A', ENT_QUOTES, 'UTF-8');
$safeMessage = nl2br(htmlspecialchars($message, ENT_QUOTES, 'UTF-8'));

$emailSubject = $subject !== '' ? $subject : 'New contact form submission';

$html = "<h2>New contact form submission</h2>"
      . "<p><strong>Name:</strong> {$safeName}</p>"
      . "<p><strong>Email:</strong> {$safeEmail}</p>"
      . "<p><strong>Subject:</strong> {$safeSubject}</p>"
      . "<p><strong>Message:</strong><br>{$safeMessage}</p>";

$text = "New contact form submission\n\n"
      . "Name: " . ($name !== '' ? $name : 'N/A') . "\n"
      . "Email: {$email}\n"
      . "Subject: " . ($subject !== '' ? $subject : 'N/A') . "\n\n"
      . "Message:\n{$message}\n";

$payload = [
    'from'     => $contactFrom,
    'to'       => [$contactTo],
    'reply_to' => $email,
    'subject'  => $emailSubject,
    'html'     => $html,
    'text'     => $text,
];

if ($contactCc) {
    $payload['cc'] = [$contactCc];
}

$ch = curl_init('https://api.resend.com/emails');
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST           => true,
    CURLOPT_POSTFIELDS     => json_encode($payload),
    CURLOPT_HTTPHEADER     => [
        'Authorization: Bearer ' . $apiKey,
        'Content-Type: application/json',
    ],
    CURLOPT_TIMEOUT        => 15,
]);

$response  = curl_exec($ch);
$curlError = curl_error($ch);
$httpCode  = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($response === false) {
    error_log('mail.php: Resend cURL error: ' . $curlError);
    respond(false, spamMessage($lang), 502);
}

if ($httpCode < 200 || $httpCode >= 300) {
    error_log('mail.php: Resend API error (' . $httpCode . '): ' . $response);
    respond(false, spamMessage($lang), 502);
}

respond(true, 'Message sent successfully.');
