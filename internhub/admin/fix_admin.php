<?php
require_once '../includes/db.php';

// First, check if admin exists
$check = $pdo->prepare("SELECT * FROM users WHERE email = 'admin@internhub.com'");
$check->execute();
$existing = $check->fetch();

$hash = password_hash('admin123', PASSWORD_DEFAULT);

if ($existing) {
    // Update existing
    $update = $pdo->prepare("UPDATE users SET `password` = :pass, role = 'admin' WHERE email = 'admin@internhub.com'");
    $update->execute([':pass' => $hash]);
    echo "✅ Updated existing admin account<br>";
} else {
    // Create new
    $insert = $pdo->prepare("INSERT INTO users (full_name, email, `password`, role, created_at) VALUES ('System Admin', 'admin@internhub.com', :pass, 'admin', NOW())");
    $insert->execute([':pass' => $hash]);
    echo "✅ Created new admin account<br>";
}

// Verify
$verify = $pdo->prepare("SELECT * FROM users WHERE email = 'admin@internhub.com'");
$verify->execute();
$admin = $verify->fetch();

echo "<hr>";
echo "Email: admin@internhub.com<br>";
echo "Password: admin123<br>";
echo "Role: " . $admin['role'] . "<br>";

if (password_verify('admin123', $admin['password'])) {
    echo "✅ Password verification: WORKING<br>";
    echo "<a href='/internhub/admin/login.php'>Go to Admin Login →</a>";
} else {
    echo "❌ Password verification: FAILED<br>";
}
?>