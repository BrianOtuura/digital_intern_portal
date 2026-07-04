<?php
session_start();
require_once '../../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /internhub/auth/register.php');
    exit;
}

$role         = trim($_POST['role']         ?? '');
$full_name    = trim($_POST['full_name']    ?? '');
$email        = trim($_POST['email']        ?? '');
$password     = trim($_POST['password']     ?? '');
$confirm      = trim($_POST['confirm_password'] ?? '');

// ── Basic validation ──────────────────────────────────────────────────────
if (!$full_name || !$email || !$password || !$confirm) {
    header('Location: /internhub/auth/register.php?type=' . $role .
           '&error=' . urlencode('Please fill in all required fields.'));
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: /internhub/auth/register.php?type=' . $role .
           '&error=' . urlencode('Please enter a valid email address.'));
    exit;
}

// ── Student email restriction ─────────────────────────────────────────────
if ($role === 'student') {
    if (!str_ends_with(strtolower($email), '@stu.ac.ug')) {
        header('Location: /internhub/auth/register.php?type=student' .
               '&error=' . urlencode('Students must register with their official UICT email ending in @stu.ac.ug'));
        exit;
    }
}

if ($password !== $confirm) {
    header('Location: /internhub/auth/register.php?type=' . $role .
           '&error=' . urlencode('Passwords do not match.'));
    exit;
}

if (strlen($password) < 8) {
    header('Location: /internhub/auth/register.php?type=' . $role .
           '&error=' . urlencode('Password must be at least 8 characters.'));
    exit;
}

// ── Check email not already registered ───────────────────────────────────
$stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email");
$stmt->execute([':email' => $email]);
if ($stmt->fetch()) {
    header('Location: /internhub/auth/register.php?type=' . $role .
           '&error=' . urlencode('An account with this email already exists.'));
    exit;
}

// ── Hash password ─────────────────────────────────────────────────────────
$hashed = password_hash($password, PASSWORD_DEFAULT);

// ── Insert user ───────────────────────────────────────────────────────────
try {
    if ($role === 'student') {
        $student_id = trim($_POST['student_id'] ?? '');
        $course     = trim($_POST['course']     ?? '');
        $study_year = trim($_POST['study_year'] ?? '');

        $stmt = $pdo->prepare("
            INSERT INTO users (full_name, email, password, role, student_id, course, study_year)
            VALUES (:full_name, :email, :password, 'student', :student_id, :course, :study_year)
        ");
        $stmt->execute([
            ':full_name'  => $full_name,
            ':email'      => $email,
            ':password'   => $hashed,
            ':student_id' => $student_id,
            ':course'     => $course,
            ':study_year' => $study_year,
        ]);

    } else {
        $company_name = trim($_POST['company_name'] ?? '');
        $industry     = trim($_POST['industry']     ?? '');
        $phone        = trim($_POST['phone']        ?? '');

        $stmt = $pdo->prepare("
            INSERT INTO users (full_name, email, password, role, company_name, industry, phone)
            VALUES (:full_name, :email, :password, 'company', :company_name, :industry, :phone)
        ");
        $stmt->execute([
            ':full_name'    => $full_name,
            ':email'        => $email,
            ':password'     => $hashed,
            ':company_name' => $company_name,
            ':industry'     => $industry,
            ':phone'        => $phone,
        ]);
    }

    header('Location: /internhub/auth/login.php?success=1&error=' .
           urlencode('Account created successfully. Please sign in.'));
    exit;

} catch (PDOException $e) {
    header('Location: /internhub/auth/register.php?type=' . $role .
           '&error=' . urlencode('Something went wrong. Please try again.'));
    exit;
}