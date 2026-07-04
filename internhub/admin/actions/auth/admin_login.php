<?php
session_start();
require_once '../../../includes/db.php';

// Turn on error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /internhub/admin/login.php?error=' . urlencode('Invalid request method'));
    exit;
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if (empty($email) || empty($password)) {
    header('Location: /internhub/admin/login.php?error=' . urlencode('Email and password are required'));
    exit;
}

// Debug: Check if admin exists
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
$stmt->execute([':email' => $email]);
$admin = $stmt->fetch();

if (!$admin) {
    // User not found - debug info
    error_log("Admin login failed: User not found - " . $email);
    header('Location: /internhub/admin/login.php?error=' . urlencode('Invalid credentials (user not found)'));
    exit;
}

// Check role
if ($admin['role'] !== 'admin') {
    error_log("Admin login failed: Wrong role - " . $admin['role']);
    header('Location: /internhub/admin/login.php?error=' . urlencode('Invalid credentials (not admin)'));
    exit;
}

// Verify password
if (!password_verify($password, $admin['password'])) {
    error_log("Admin login failed: Password mismatch for " . $email);
    header('Location: /internhub/admin/login.php?error=' . urlencode('Invalid credentials (wrong password)'));
    exit;
}

// Success
$_SESSION['admin_id'] = $admin['id'];
$_SESSION['admin_name'] = $admin['full_name'];
$_SESSION['admin_role'] = $admin['role'];

error_log("Admin login success: " . $email);
header('Location: /internhub/admin/index.php');
exit;
?>