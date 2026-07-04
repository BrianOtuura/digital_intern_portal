<?php
session_start();
if (!isset($_SESSION['admin_id']) || $_SESSION['admin_role'] !== 'admin') {
    header('Location: /internhub/admin/login.php');
    exit;
}
require_once '../includes/db.php';

$users = $pdo->query("SELECT id, full_name, email, role, created_at FROM users ORDER BY created_at DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<title>Manage Users — Admin</title>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:wght@400;600;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
<link rel="stylesheet" href="/internhub/css/style.css">
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:'Plus Jakarta Sans',sans-serif;background:#F8FAF9}
.sidebar{position:fixed;top:0;left:0;width:260px;height:100vh;background:#062E22;display:flex;flex-direction:column}
.sb-header{padding:1.5rem;border-bottom:1px solid rgba(255,255,255,.1)}
.sb-header h2{font-family:'Fraunces',serif;font-size:1rem;color:#fff}
.sb-nav{padding:1rem .75rem;flex:1}
.sb-item{display:flex;align-items:center;gap:.8rem;padding:.7rem .9rem;border-radius:10px;font-size:.85rem;font-weight:500;color:rgba(255,255,255,.5);transition:all .2s;margin-bottom:.2rem}
.sb-item:hover{background:rgba(255,255,255,.08);color:#fff}
.sb-item.active{background:rgba(29,184,133,.15);color:#1DB885}
.sb-logout{margin-top:auto;padding:1rem .75rem;border-top:1px solid rgba(255,255,255,.1)}
.sb-logout a{display:flex;align-items:center;gap:.8rem;padding:.7rem .9rem;border-radius:10px;font-size:.85rem;color:rgba(255,100,100,.6)}
.main{margin-left:260px;padding:1.5rem}
.topbar{background:#fff;border-radius:16px;padding:1rem 1.5rem;margin-bottom:1.5rem}
.topbar h1{font-family:'Fraunces',serif;font-size:1.3rem}
.card{background:#fff;border:1px solid #EEF1EF;border-radius:20px;overflow:hidden}
table{width:100%;border-collapse:collapse}
th,td{padding:14px 16px;text-align:left;border-bottom:1px solid #EEF1EF;font-size:.85rem}
th{background:#F8FAF9;font-weight:600}
.role-student{background:#EDF9F5;color:#128A69;padding:4px 10px;border-radius:99px;font-size:.7rem;font-weight:600;display:inline-block}
.role-company{background:#FBF3D9;color:#D4A017;padding:4px 10px;border-radius:99px;font-size:.7rem;font-weight:600;display:inline-block}
.role-admin{background:#F3E8FF;color:#9333EA;padding:4px 10px;border-radius:99px;font-size:.7rem;font-weight:600;display:inline-block}
</style>
</head>
<body>
<div class="sidebar">
  <div class="sb-header"><h2>⚙️ Admin Portal</h2></div>
  <div class="sb-nav">
    <a href="/internhub/admin/index.php" class="sb-item">🏠 Dashboard</a>
    <a href="/internhub/admin/manage-listings.php" class="sb-item">📋 Listings</a>
    <a href="/internhub/admin/manage-payments.php" class="sb-item">💰 Payments</a>
    <a href="/internhub/admin/manage-users.php" class="sb-item active">👥 Users</a>
    <a href="/internhub/admin/verify-placement.php" class="sb-item">✅ Placements</a>
  </div>
  <div class="sb-logout"><a href="/internhub/admin/actions/admin_logout.php">🚪 Logout</a></div>
</div>

<div class="main">
  <div class="topbar"><h1>All Users</h1></div>
  <div class="card">
    <table>
      <thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>Registered</th></tr></thead>
      <tbody>
        <?php foreach($users as $u): ?>
        <tr>
          <td><?php echo $u['id']; ?></td>
          <td><strong><?php echo htmlspecialchars($u['full_name']); ?></strong></td>
          <td><?php echo htmlspecialchars($u['email']); ?></td>
          <td><span class="role-<?php echo $u['role']; ?>"><?php echo ucfirst($u['role']); ?></span></td>
          <td><?php echo date('d M Y', strtotime($u['created_at'])); ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
</body>
</html>