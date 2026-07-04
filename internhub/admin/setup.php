<?php
require_once '../includes/db.php';

// Check if admin exists
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = 'admin@internhub.com'");
$stmt->execute();
$admin = $stmt->fetch();

if ($admin) {
    // Force update password with correct hash
    $newHash = password_hash('admin123', PASSWORD_DEFAULT);
    $update = $pdo->prepare("UPDATE users SET password = :pass, role = 'admin' WHERE email = 'admin@internhub.com'");
    $update->execute([':pass' => $newHash]);
    echo "✅ Admin password RESET to: admin123<br>";
    echo "Hash: " . $newHash . "<br>";
} else {
    // Create new admin
    $hash = password_hash('admin123', PASSWORD_DEFAULT);
    $insert = $pdo->prepare("INSERT INTO users (full_name, email, password, role, created_at) VALUES ('System Administrator', 'admin@internhub.com', :pass, 'admin', NOW())");
    $insert->execute([':pass' => $hash]);
    echo "✅ Admin account CREATED<br>";
    echo "Email: admin@internhub.com<br>";
    echo "Password: admin123<br>";
}

// Verify the password works
$testStmt = $pdo->prepare("SELECT * FROM users WHERE email = 'admin@internhub.com'");
$testStmt->execute();
$testAdmin = $testStmt->fetch();
echo "<hr>";
echo "Stored hash: " . $testAdmin['password'] . "<br>";
echo "Password verify test: ";
if (password_verify('admin123', $testAdmin['password'])) {
    echo "✅ WORKING<br>";
} else {
    echo "❌ FAILED - hash mismatch<br>";
}
?>