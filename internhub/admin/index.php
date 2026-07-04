<?php
session_start();
if (!isset($_SESSION['admin_id']) || $_SESSION['admin_role'] !== 'admin') {
    header('Location: /internhub/admin/login.php');
    exit;
}
require_once '../includes/db.php';

// Stats
$pendingListings = $pdo->query("SELECT COUNT(*) FROM internships WHERE status = 'pending'")->fetchColumn();
$approvedListings = $pdo->query("SELECT COUNT(*) FROM internships WHERE status = 'approved'")->fetchColumn();
$totalListings = $pdo->query("SELECT COUNT(*) FROM internships")->fetchColumn();

$pendingPayments = $pdo->query("SELECT COUNT(*) FROM readiness_enrollments WHERE status = 'pending'")->fetchColumn();
$totalStudents = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'student'")->fetchColumn();
$totalCompanies = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'company'")->fetchColumn();

$totalApplications = $pdo->query("SELECT COUNT(*) FROM applications")->fetchColumn();

// Recent applications
$recentApps = $pdo->query("
    SELECT a.*, i.title, i.company, u.full_name as student_name 
    FROM applications a 
    JOIN internships i ON a.listing_id = i.id 
    JOIN users u ON a.student_id = u.id 
    ORDER BY a.created_at DESC LIMIT 5
")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Admin Dashboard — DIP</title>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:wght@400;600;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
<link rel="stylesheet" href="/internhub/css/style.css">
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:'Plus Jakarta Sans',sans-serif;background:#F8FAF9;color:#0D1F19}
a{text-decoration:none;color:inherit}
.sidebar{position:fixed;top:0;left:0;width:260px;height:100vh;background:#062E22;display:flex;flex-direction:column}
.sb-header{padding:1.5rem;border-bottom:1px solid rgba(255,255,255,.1)}
.sb-header h2{font-family:'Fraunces',serif;font-size:1rem;color:#fff}
.sb-header p{font-size:.7rem;color:rgba(255,255,255,.4);margin-top:4px}
.sb-nav{padding:1rem .75rem;flex:1}
.sb-item{display:flex;align-items:center;gap:.8rem;padding:.7rem .9rem;border-radius:10px;font-size:.85rem;font-weight:500;color:rgba(255,255,255,.5);transition:all .2s;margin-bottom:.2rem}
.sb-item:hover{background:rgba(255,255,255,.08);color:#fff}
.sb-item.active{background:rgba(29,184,133,.15);color:#1DB885}
.sb-logout{margin-top:auto;padding:1rem .75rem;border-top:1px solid rgba(255,255,255,.1)}
.sb-logout a{display:flex;align-items:center;gap:.8rem;padding:.7rem .9rem;border-radius:10px;font-size:.85rem;color:rgba(255,100,100,.6);transition:all .2s}
.sb-logout a:hover{background:rgba(248,113,113,.1);color:#f87171}
.main{margin-left:260px;padding:1.5rem}
.topbar{background:#fff;border-radius:16px;padding:1rem 1.5rem;margin-bottom:1.5rem;display:flex;justify-content:space-between;align-items:center;border:1px solid #EEF1EF}
.topbar h1{font-family:'Fraunces',serif;font-size:1.3rem;font-weight:600}
.admin-badge{background:#EDF9F5;color:#128A69;padding:5px 12px;border-radius:99px;font-size:.75rem;font-weight:600}
.stats{display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:1rem;margin-bottom:1.5rem}
.stat-card{background:#fff;border:1px solid #EEF1EF;border-radius:20px;padding:1.2rem 1.3rem}
.stat-num{font-family:'Fraunces',serif;font-size:2rem;font-weight:700;margin-bottom:5px}
.stat-label{font-size:.75rem;color:#8A9E96}
.card{background:#fff;border:1px solid #EEF1EF;border-radius:20px;overflow:hidden;margin-bottom:1.5rem}
.card-header{padding:1.2rem 1.5rem;border-bottom:1px solid #EEF1EF;display:flex;justify-content:space-between;align-items:center}
.card-header h3{font-family:'Fraunces',serif;font-weight:600;font-size:1rem}
.card-link{font-size:.8rem;color:#128A69;font-weight:600}
table{width:100%;border-collapse:collapse}
th,td{padding:12px 16px;text-align:left;border-bottom:1px solid #EEF1EF;font-size:.85rem}
th{background:#F8FAF9;font-weight:600;color:#4A5E56}
.status-pending{background:#FFFBEB;color:#B45309;padding:4px 10px;border-radius:99px;font-size:.7rem;font-weight:600}
.status-approved{background:#F0FDF4;color:#166534;padding:4px 10px;border-radius:99px;font-size:.7rem;font-weight:600}
.btn-sm{padding:5px 12px;border-radius:8px;font-size:.75rem;font-weight:600;border:none;cursor:pointer}
.btn-approve{background:#16A34A;color:#fff}
.btn-reject{background:#DC2626;color:#fff}
.btn-view{background:#EEF1EF;color:#4A5E56}
</style>
</head>
<body>
<div class="sidebar">
  <div class="sb-header">
    <h2>⚙️ Admin Portal</h2>
    <p>Digital Internship Platform</p>
  </div>
  <div class="sb-nav">
    <a href="/internhub/admin/index.php" class="sb-item active">🏠 Dashboard</a>
    <a href="/internhub/admin/manage-listings.php" class="sb-item">📋 Manage Listings</a>
    <a href="/internhub/admin/manage-payments.php" class="sb-item">💰 Payments</a>
    <a href="/internhub/admin/manage-users.php" class="sb-item">👥 Users</a>
    <a href="/internhub/admin/verify-placement.php" class="sb-item">✅ Placements</a>
  </div>
  <div class="sb-logout">
    <a href="/internhub/admin/actions/admin_logout.php">🚪 Logout</a>
  </div>
</div>

<div class="main">
  <div class="topbar">
    <h1>Dashboard</h1>
    <div class="admin-badge">Welcome, <?php echo htmlspecialchars($_SESSION['admin_name']); ?></div>
  </div>

  <div class="stats">
    <div class="stat-card"><div class="stat-num"><?php echo $pendingListings; ?></div><div class="stat-label">Pending Listings</div></div>
    <div class="stat-card"><div class="stat-num"><?php echo $approvedListings; ?></div><div class="stat-label">Active Listings</div></div>
    <div class="stat-card"><div class="stat-num"><?php echo $pendingPayments; ?></div><div class="stat-label">Pending Payments</div></div>
    <div class="stat-card"><div class="stat-num"><?php echo $totalApplications; ?></div><div class="stat-label">Applications</div></div>
    <div class="stat-card"><div class="stat-num"><?php echo $totalStudents; ?></div><div class="stat-label">Students</div></div>
    <div class="stat-card"><div class="stat-num"><?php echo $totalCompanies; ?></div><div class="stat-label">Companies</div></div>
  </div>

  <div class="card">
    <div class="card-header">
      <h3>Recent Applications</h3>
      <a href="/internhub/admin/manage-listings.php" class="card-link">View all →</a>
    </div>
    <table>
      <thead><tr><th>Student</th><th>Internship</th><th>Company</th><th>Applied</th><th>Status</th></tr></thead>
      <tbody>
        <?php foreach($recentApps as $app): ?>
        <tr>
          <td><?php echo htmlspecialchars($app['student_name']); ?></td>
          <td><?php echo htmlspecialchars($app['title']); ?></td>
          <td><?php echo htmlspecialchars($app['company']); ?></td>
          <td><?php echo date('d M', strtotime($app['created_at'])); ?></td>
          <td><span class="status-<?php echo $app['status']; ?>"><?php echo ucfirst($app['status']); ?></span></td>
        </tr>
        <?php endforeach; ?>
        <?php if(empty($recentApps)): ?>
        <tr><td colspan="5" style="text-align:center;padding:2rem">No applications yet</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
</body>
</html>