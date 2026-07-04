<?php
session_start();
require_once '../../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /internhub/auth/login.php');
    exit;
}

$email    = trim($_POST['email']    ?? '');
$password = trim($_POST['password'] ?? '');
$next     = trim($_POST['next']     ?? '');

if (!$email || !$password) {
    header('Location: /internhub/auth/login.php?error=' .
           urlencode('Please enter your email and password.'));
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
$stmt->execute([':email' => $email]);
$user = $stmt->fetch();

if (!$user || !password_verify($password, $user['password'])) {
    header('Location: /internhub/auth/login.php?error=' .
           urlencode('Invalid email or password.'));
    exit;
}

// ── Set session ───────────────────────────────────────────────────────────
$_SESSION['user_id']   = $user['id'];
$_SESSION['user_name'] = $user['full_name'];
$_SESSION['user_role'] = $user['role'];
$_SESSION['user_email']= $user['email'];

// ── Redirect based on role or intended destination ────────────────────────
if ($next) {
    header('Location: ' . $next);
} elseif ($user['role'] === 'company') {
    header('Location: /internhub/auth/dashboard.php');
} else {
    header('Location: /internhub/auth/dashboard.php');
}
exit;