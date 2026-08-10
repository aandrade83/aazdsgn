<?php

header('Content-Type: application/json; charset=utf-8');

function respond($success, $message, $httpCode = 200) {
    http_response_code($httpCode);
    echo json_encode(['success' => $success, 'message' => $message]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    respond(false, 'Method not allowed.', 405);
}

$name    = isset($_POST['name']) ? trim($_POST['name']) : '';
$email   = isset($_POST['email']) ? trim($_POST['email']) : '';
$subject = isset($_POST['subject']) ? trim($_POST['subject']) : '';
$message = isset($_POST['message']) ? trim($_POST['message']) : '';

if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    respond(false, 'Please provide a valid email address.', 422);
}

if ($message === '') {
    respond(false, 'Please provide a message.', 422);
}

$apiKey      = getenv('RESEND_API_KEY');
$contactTo   = getenv('CONTACT_TO');
$contactCc   = getenv('CONTACT_CC');
$contactFrom = getenv('CONTACT_FROM');

if (!$apiKey || !$contactTo || !$contactFrom) {
    error_log('mail.php: missing RESEND_API_KEY/CONTACT_TO/CONTACT_FROM environment variables.');
    respond(false, 'Unable to send message at this time.', 500);
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
    respond(false, 'Unable to send message at this time.', 502);
}

if ($httpCode < 200 || $httpCode >= 300) {
    error_log('mail.php: Resend API error (' . $httpCode . '): ' . $response);
    respond(false, 'Unable to send message at this time.', 502);
}

respond(true, 'Message sent successfully.');
