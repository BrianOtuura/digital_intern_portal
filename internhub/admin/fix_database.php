<?php
require_once '../includes/db.php';

try {
    // Step 1: Modify the table to allow 'admin' role
    $pdo->exec("ALTER TABLE `users` MODIFY COLUMN `role` ENUM('student', 'company', 'admin') NOT NULL DEFAULT 'student'");
    echo "✅ Table structure updated<br>";
} catch (PDOException $e) {
    echo "⚠️ Could not modify ENUM, trying alternative method...<br>";
    
    // Alternative method
    $pdo->exec("ALTER TABLE `users` ADD COLUMN `role_temp` VARCHAR(20) DEFAULT 'student'");
    $pdo->exec("UPDATE `users` SET `role_temp` = `role`");
    $pdo->exec("ALTER TABLE `users` DROP COLUMN `role`");
    $pdo->exec("ALTER TABLE `users` CHANGE COLUMN `role_temp` `role` VARCHAR(20) NOT NULL DEFAULT 'student'");
    echo "✅ Table recreated with VARCHAR column<br>";
}

// Step 2: Create/Update admin user
$hash = password_hash('admin123', PASSWORD_DEFAULT);

$check = $pdo->prepare("SELECT * FROM users WHERE email = 'admin@internhub.com'");
$check->execute();
$existing = $check->fetch();

if ($existing) {
    $update = $pdo->prepare("UPDATE users SET `password` = :pass, `role` = 'admin' WHERE email = 'admin@internhub.com'");
    $update->execute([':pass' => $hash]);
    echo "✅ Admin user UPDATED<br>";
} else {
    $insert = $pdo->prepare("INSERT INTO users (full_name, email, `password`, `role`, created_at) VALUES ('System Admin', 'admin@internhub.com', :pass, 'admin', NOW())");
    $insert->execute([':pass' => $hash]);
    echo "✅ Admin user CREATED<br>";
}

// Step 3: Verify
$verify = $pdo->prepare("SELECT * FROM users WHERE email = 'admin@internhub.com'");
$verify->execute();
$admin = $verify->fetch();

echo "<hr>";
echo "<strong>Admin Account Details:</strong><br>";
echo "Email: admin@internhub.com<br>";
echo "Password: admin123<br>";
echo "Role: " . $admin['role'] . "<br>";
echo "ID: " . $admin['id'] . "<br>";

if (password_verify('admin123', $admin['password'])) {
    echo "<span style='color:green'>✅ Password verification: WORKING</span><br>";
    echo "<br><a href='/internhub/admin/login.php' style='background:#0B4D3B;color:#fff;padding:10px 20px;text-decoration:none;border-radius:8px'>Go to Admin Login →</a>";
} else {
    echo "<span style='color:red'>❌ Password verification: FAILED</span><br>";
}

// Show all roles in database for debugging
$roles = $pdo->query("SELECT DISTINCT role FROM users")->fetchAll();
echo "<hr><strong>Current roles in database:</strong> ";
foreach($roles as $r) {
    echo $r['role'] . " ";
}
?>