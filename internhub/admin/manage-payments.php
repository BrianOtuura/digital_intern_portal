<?php
session_start();
if (!isset($_SESSION['admin_id']) || $_SESSION['admin_role'] !== 'admin') {
    header('Location: /internhub/admin/login.php');
    exit;
}
require_once '../includes/db.php';

// Handle manual certification (if needed)
if (isset($_GET['certify']) && isset($_GET['user_id'])) {
    $userId = $_GET['user_id'];
    $certCode = 'DIP-CERT-' . strtoupper(uniqid());
    
    $update = $pdo->prepare("UPDATE users SET readiness_certified = 1, readiness_certificate_code = :code WHERE id = :id");
    $update->execute([':code' => $certCode, ':id' => $userId]);
    
    header('Location: /internhub/admin/manage-payments.php?certified=1');
    exit;
}

// Get all readiness enrollments
$enrollments = $pdo->query("
    SELECT r.*, u.full_name, u.email, u.readiness_certified 
    FROM readiness_enrollments r 
    JOIN users u ON r.user_id = u.id 
    ORDER BY r.created_at DESC
")->fetchAll();

// Get certified students
$certified = $pdo->query("
    SELECT id, full_name, email, readiness_tier, readiness_certificate_code 
    FROM users 
    WHERE readiness_certified = 1
    ORDER BY updated_at DESC
")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<title>Manage Payments — Admin</title>
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
.card{background:#fff;border:1px solid #EEF1EF;border-radius:20px;overflow:hidden;margin-bottom:1.5rem}
.card-header{padding:1.2rem 1.5rem;border-bottom:1px solid #EEF1EF}
.card-header h3{font-family:'Fraunces',serif;font-weight:600}
table{width:100%;border-collapse:collapse}
th,td{padding:12px 16px;text-align:left;border-bottom:1px solid #EEF1EF;font-size:.85rem}
th{background:#F8FAF9;font-weight:600}
.badge-certified{background:#F0FDF4;color:#166534;padding:4px 10px;border-radius:99px;font-size:.7rem}
.badge-pending{background:#FFFBEB;color:#B45309;padding:4px 10px;border-radius:99px;font-size:.7rem}
.btn-small{padding:5px 12px;background:#128A69;color:#fff;border:none;border-radius:8px;cursor:pointer;font-size:.7rem;text-decoration:none;display:inline-block}
.alert{background:#EDF9F5;border:1px solid #C5F0E3;border-radius:12px;padding:.8rem 1rem;margin-bottom:1rem}
</style>
</head>
<body>
<div class="sidebar">
  <div class="sb-header"><h2>⚙️ Admin Portal</h2></div>
  <div class="sb-nav">
    <a href="/internhub/admin/index.php" class="sb-item">🏠 Dashboard</a>
    <a href="/internhub/admin/manage-listings.php" class="sb-item">📋 Listings</a>
    <a href="/internhub/admin/manage-payments.php" class="sb-item active">💰 Payments</a>
    <a href="/internhub/admin/manage-users.php" class="sb-item">👥 Users</a>
    <a href="/internhub/admin/verify-placement.php" class="sb-item">✅ Placements</a>
  </div>
  <div class="sb-logout"><a href="/internhub/admin/actions/admin_logout.php">🚪 Logout</a></div>
</div>

<div class="main">
  <div class="topbar"><h1>💰 Readiness Program Payments</h1></div>

  <?php if(isset($_GET['certified'])): ?>
    <div class="alert">✅ Student has been certified successfully.</div>
  <?php endif; ?>

  <div class="card">
    <div class="card-header"><h3>Enrollment Requests</h3></div>
    <table>
      <thead><tr><th>Student</th><th>Email</th><th>Tier</th><th>Payment Ref</th><th>Status</th><th>Action</th></tr></thead>
      <tbody>
        <?php foreach($enrollments as $e): ?>
        <tr>
          <td><?php echo htmlspecialchars($e['full_name']); ?></td>
          <td><?php echo htmlspecialchars($e['email']); ?></td>
          <td><?php echo ucfirst($e['tier']); ?></td>
          <td><code><?php echo htmlspecialchars($e['payment_ref']); ?></code></td>
          <td>
            <?php if($e['readiness_certified']): ?>
              <span class="badge-certified">✅ Certified</span>
            <?php else: ?>
              <span class="badge-pending">Pending</span>
            <?php endif; ?>
           </td>
          <td>
            <?php if(!$e['readiness_certified']): ?>
              <a href="?certify=1&user_id=<?php echo $e['user_id']; ?>" class="btn-small" onclick="return confirm('Certify this student?')">✅ Confirm & Certify</a>
            <?php else: ?>
              —
            <?php endif; ?>
           </td>
        </tr>
        <?php endforeach; ?>
        <?php if(empty($enrollments)): ?>
          <tr><td colspan="6" style="text-align:center;padding:2rem">No enrollment requests yet</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <div class="card">
    <div class="card-header"><h3>Certified Students</h3></div>
    <table>
      <thead><tr><th>Name</th><th>Email</th><th>Tier</th><th>Certificate Code</th></tr></thead>
      <tbody>
        <?php foreach($certified as $c): ?>
        <tr>
          <td><?php echo htmlspecialchars($c['full_name']); ?></td>
          <td><?php echo htmlspecialchars($c['email']); ?></td>
          <td><?php echo ucfirst($c['readiness_tier'] ?? 'Foundation'); ?></td>
          <td><code><?php echo htmlspecialchars($c['readiness_certificate_code']); ?></code></td>
        </tr>
        <?php endforeach; ?>
        <?php if(empty($certified)): ?>
          <tr><td colspan="4" style="text-align:center;padding:2rem">No certified students yet</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
</body>
</html>