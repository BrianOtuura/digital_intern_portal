<?php
session_start();
if (!isset($_SESSION['admin_id']) || $_SESSION['admin_role'] !== 'admin') {
    header('Location: /internhub/admin/login.php');
    exit;
}
require_once '../includes/db.php';

// Confirm placement
if (isset($_GET['confirm']) && isset($_GET['placement_id'])) {
    $placementId = (int)$_GET['placement_id'];
    
    // Update placement
    $pdo->prepare("UPDATE placements SET admin_confirmed = 1, confirmation_date = NOW() WHERE id = ?")->execute([$placementId]);
    
    // Update user's total placements count
    $pdo->exec("UPDATE users SET total_placements = total_placements + 1 WHERE id = (SELECT student_id FROM placements WHERE id = $placementId)");
    
    header('Location: /internhub/admin/verify-placement.php?confirmed=1');
    exit;
}

// Get all placements
$placements = $pdo->query("
    SELECT p.*, u.full_name as student_name, u.email, u.total_placements, i.title, i.company 
    FROM placements p 
    JOIN users u ON p.student_id = u.id 
    JOIN internships i ON p.internship_id = i.id 
    ORDER BY p.created_at DESC
")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<title>Verify Placements — Admin</title>
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
.main{margin-left:260px;padding:1.5rem}
.topbar{background:#fff;border-radius:16px;padding:1rem 1.5rem;margin-bottom:1.5rem}
.topbar h1{font-family:'Fraunces',serif;font-size:1.3rem}
.card{background:#fff;border:1px solid #EEF1EF;border-radius:20px;overflow:hidden}
.card-header{padding:1.2rem 1.5rem;border-bottom:1px solid #EEF1EF}
table{width:100%;border-collapse:collapse}
th,td{padding:12px 16px;text-align:left;border-bottom:1px solid #EEF1EF;font-size:.85rem}
th{background:#F8FAF9}
.badge-confirmed{background:#F0FDF4;color:#166534;padding:4px 10px;border-radius:99px;font-size:.7rem}
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
    <a href="/internhub/admin/manage-payments.php" class="sb-item">💰 Payments</a>
    <a href="/internhub/admin/manage-users.php" class="sb-item">👥 Users</a>
    <a href="/internhub/admin/verify-placement.php" class="sb-item active">✅ Placements</a>
  </div>
  <div class="sb-logout"><a href="/internhub/admin/actions/admin_logout.php">🚪 Logout</a></div>
</div>

<div class="main">
  <div class="topbar"><h1>✅ Placement Verification</h1></div>

  <?php if(isset($_GET['confirmed'])): ?>
    <div class="alert">✅ Placement has been confirmed. Student notified.</div>
  <?php endif; ?>

  <div class="card">
    <div class="card-header"><h3>Pending Placements</h3></div>
    <table>
      <thead><tr><th>Student</th><th>Internship</th><th>Company</th><th>Requested</th><th>Status</th><th>Action</th></tr></thead>
      <tbody>
        <?php 
        $hasPending = false;
        foreach($placements as $p): 
            if(!$p['admin_confirmed']): 
                $hasPending = true;
        ?>
        <tr>
          <td><?php echo htmlspecialchars($p['student_name']); ?> <br><span style="font-size:.7rem;color:#666"><?php echo $p['total_placements']; ?> prior placements</span></td>
          <td><?php echo htmlspecialchars($p['title']); ?></td>
          <td><?php echo htmlspecialchars($p['company']); ?></td>
          <td><?php echo date('d M Y', strtotime($p['created_at'])); ?></td>
          <td><span class="badge-pending">Pending Admin</span></td>
          <td><a href="?confirm=1&placement_id=<?php echo $p['id']; ?>" class="btn-small" onclick="return confirm('Confirm this placement?')">Confirm Placement</a></td>
        </tr>
        <?php endif; endforeach; ?>
        <?php if(!$hasPending): ?>
          <tr><td colspan="6" style="text-align:center;padding:2rem">No pending placements</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <div class="card" style="margin-top:1.5rem">
    <div class="card-header"><h3>Confirmed Placements</h3></div>
    <table>
      <thead><tr><th>Student</th><th>Internship</th><th>Company</th><th>Confirmed On</th><th>Status</th></tr></thead>
      <tbody>
        <?php 
        $hasConfirmed = false;
        foreach($placements as $p): 
            if($p['admin_confirmed']): 
                $hasConfirmed = true;
        ?>
        <tr>
          <td><?php echo htmlspecialchars($p['student_name']); ?></td>
          <td><?php echo htmlspecialchars($p['title']); ?></td>
          <td><?php echo htmlspecialchars($p['company']); ?></td>
          <td><?php echo date('d M Y', strtotime($p['confirmation_date'])); ?></td>
          <td><span class="badge-confirmed">✅ Confirmed</span></td>
        </tr>
        <?php endif; endforeach; ?>
        <?php if(!$hasConfirmed): ?>
          <tr><td colspan="5" style="text-align:center;padding:2rem">No confirmed placements yet</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
</body>
</html>