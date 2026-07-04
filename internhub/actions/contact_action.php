<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /internhub/contact.php');
    exit;
}

$name    = trim($_POST['name']    ?? '');
$email   = trim($_POST['email']   ?? '');
$subject = trim($_POST['subject'] ?? '');
$message = trim($_POST['message'] ?? '');
$role    = trim($_POST['role']    ?? '');

if (!$name || !$email || !$subject || !$message) {
    header('Location: /internhub/contact.php?error=' . urlencode('Please fill in all required fields.'));
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: /internhub/contact.php?error=' . urlencode('Please enter a valid email address.'));
    exit;
}

// In a live deployment you would send an email here using mail() or PHPMailer.
// For now we just redirect with a success flag.
header('Location: /internhub/contact.php?sent=1');
exit;
