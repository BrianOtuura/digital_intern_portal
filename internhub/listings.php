<?php
session_start();
require_once 'includes/db.php';

function getInternshipStatus($deadline) {
    $today = new DateTime();
    $deadlineDate = new DateTime($deadline);
    
    if ($deadlineDate < $today) {
        return ['text' => 'Closed', 'class' => 'b-closed'];
    }
    
    $daysLeft = $today->diff($deadlineDate)->days;
    
    if ($daysLeft <= 7) {
        return ['text' => 'Closing Soon', 'class' => 'b-closing'];
    }
    
    return ['text' => 'Open', 'class' => 'b-open'];
}

// Get all approved internships
$stmt = $pdo->query("SELECT * FROM internships WHERE status = 'approved' ORDER BY created_at DESC");
$internships = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Browse Internships — Digital Internship Portal</title>
<link rel="preconnect" href="https://fonts.googleapis.com"/>
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,300;0,9..144,600;0,9..144,700;1,9..144,300&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
<link rel="stylesheet" href="/internhub/css/style.css">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{
  --teal-900:#062E22;--teal-800:#0B4D3B;--teal-700:#0E6B52;
  --teal-600:#128A69;--teal-400:#1DB885;--teal-200:#8EDFC5;
  --teal-100:#C5F0E3;--teal-50:#EDF9F5;
  --gold:#D4A017;--gold-lt:#FBF3D9;--gold-dk:#A67C00;
  --white:#FFFFFF;--off:#F8FAF9;
  --grey-100:#EEF1EF;--grey-200:#D8DFDB;--grey-400:#8A9E96;--grey-600:#4A5E56;--ink:#0D1F19;
  --green:#16A34A;--green-bg:#F0FDF4;--green-border:#BBF7D0;
  --amber:#B45309;--amber-bg:#FFFBEB;--amber-border:#FDE68A;
  --red:#B91C1C;--red-bg:#FFF1F1;--red-border:#FECACA;
  --shadow-sm:0 1px 4px rgba(6,46,34,.07);
  --shadow-md:0 4px 20px rgba(6,46,34,.10);
  --shadow-lg:0 12px 48px rgba(6,46,34,.13);
  --r-sm:6px;--r-md:12px;--r-lg:20px;--r-xl:28px;
  --ease:cubic-bezier(.4,0,.2,1);--t:.2s;
}
html{scroll-behavior:smooth}
body{font-family:'Plus Jakarta Sans',sans-serif;background:var(--off);color:var(--ink);min-height:100vh}
a{text-decoration:none;color:inherit}
.nav{position:sticky;top:0;z-index:200;height:68px;display:flex;align-items:center;justify-content:space-between;padding:0 2.5rem;background:rgba(255,255,255,.95);backdrop-filter:blur(14px);border-bottom:1px solid var(--grey-100)}
.nav-brand{display:flex;align-items:center;gap:10px;font-family:'Fraunces',serif;font-size:1.1rem;font-weight:700;color:var(--teal-800)}
.nav-brand-dot{width:8px;height:8px;border-radius:50%;background:var(--teal-600)}
.nav-links{display:flex;align-items:center;gap:4px}
.nav-links a{font-size:.875rem;font-weight:500;color:var(--grey-600);padding:6px 13px;border-radius:var(--r-sm)}
.nav-links a:hover{color:var(--ink);background:var(--off)}
.nav-links a.active{color:var(--teal-700);font-weight:600}
.nav-cta{background:var(--teal-800)!important;color:#fff!important;border-radius:var(--r-sm)!important;padding:8px 18px!important}
.container{max-width:1200px;margin:0 auto;padding:2rem 2.5rem}
.page-header{margin-bottom:2rem}
.page-header h1{font-family:'Fraunces',serif;font-size:2rem;font-weight:700;color:var(--ink);margin-bottom:.5rem}
.page-header p{color:var(--grey-600)}
.b-closed{background:#FEF2F2;color:#DC2626;border:1px solid #FECACA}
.b-closing{background:#FFFBEB;color:#B45309;border:1px solid #FDE68A}
.b-open{background:#F0FDF4;color:#16A34A;border:1px solid #BBF7D0}
.internship-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(350px,1fr));gap:1.5rem}
.internship-card{background:var(--white);border:1px solid var(--grey-100);border-radius:var(--r-xl);overflow:hidden;transition:all var(--t) var(--ease)}
.internship-card:hover{transform:translateY(-4px);box-shadow:var(--shadow-lg);border-color:var(--grey-200)}
.card-header{padding:1.2rem 1.5rem;border-bottom:1px solid var(--grey-100);display:flex;justify-content:space-between;align-items:flex-start}
.card-logo{width:48px;height:48px;border-radius:var(--r-md);display:flex;align-items:center;justify-content:center;font-family:'Fraunces',serif;font-weight:700;font-size:.9rem;color:#fff;flex-shrink:0}
.card-title h3{font-family:'Fraunces',serif;font-weight:700;font-size:1rem;margin-bottom:.2rem}
.card-title p{font-size:.75rem;color:var(--grey-400)}
.card-body{padding:1.2rem 1.5rem}
.card-meta{display:flex;flex-wrap:wrap;gap:.5rem;margin-bottom:1rem}
.meta-tag{font-size:.7rem;background:var(--off);padding:4px 10px;border-radius:99px;color:var(--grey-600)}
.card-desc{font-size:.85rem;color:var(--grey-600);line-height:1.6;margin-bottom:1rem}
.card-footer{padding:1rem 1.5rem;border-top:1px solid var(--grey-100);display:flex;justify-content:space-between;align-items:center}
.btn-apply{background:var(--teal-800);color:#fff;padding:8px 20px;border-radius:var(--r-sm);font-weight:600;font-size:.8rem;transition:all var(--t) var(--ease)}
.btn-apply:hover{background:var(--teal-900)}
.empty-state{text-align:center;padding:4rem;background:var(--white);border-radius:var(--r-xl);border:1px solid var(--grey-100)}
.footer{background:#040F0A;padding:3rem 0 2rem;margin-top:3rem}
.footer-inner{max-width:1200px;margin:0 auto;padding:0 2rem;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem;font-size:.78rem;color:rgba(255,255,255,.22)}
</style>
</head>
<body>

<nav class="nav">
  <a class="nav-brand" href="/internhub/index.php">
    <span class="nav-brand-dot"></span>
    Digital Internship Portal
  </a>
  <div class="nav-links">
    <a href="/internhub/index.php">Home</a>
    <a href="/internhub/listings.php" class="active">Internships</a>
    <a href="/internhub/readiness.php">Readiness Program</a>
    <a href="/internhub/partners.php">For Companies</a>
    <a href="/internhub/about.php">About</a>
    <?php if(isset($_SESSION['user_id'])): ?>
      <a href="/internhub/auth/dashboard.php">Dashboard</a>
      <a href="/internhub/auth/actions/logout.php" class="nav-cta">Log Out</a>
    <?php else: ?>
      <a href="/internhub/auth/login.php">Sign In</a>
      <a href="/internhub/auth/register.php" class="nav-cta">Get Started</a>
    <?php endif; ?>
  </div>
</nav>

<div class="container">
  <div class="page-header">
    <h1>Browse Internships</h1>
    <p>Find verified internship opportunities from Uganda's top employers</p>
  </div>

  <?php if(empty($internships)): ?>
    <div class="empty-state">
      <p style="font-size:3rem;margin-bottom:1rem">💼</p>
      <h3 style="margin-bottom:.5rem">No internships available yet</h3>
      <p style="color:var(--grey-400)">Check back soon for new opportunities.</p>
    </div>
  <?php else: ?>
    <div class="internship-grid">
      <?php foreach($internships as $internship):
        $status = getInternshipStatus($internship['deadline']);
        $deadline = new DateTime($internship['deadline']);
      ?>
      <div class="internship-card">
        <div class="card-header">
          <div class="card-logo" style="background:<?php echo htmlspecialchars($internship['logo_color']); ?>">
            <?php echo htmlspecialchars($internship['logo']); ?>
          </div>
          <span class="meta-tag" style="font-weight:600; <?php echo $status['class'] === 'b-open' ? 'background:#F0FDF4;color:#16A34A' : ($status['class'] === 'b-closing' ? 'background:#FFFBEB;color:#B45309' : 'background:#FEF2F2;color:#DC2626'); ?>">
            <?php echo $status['text']; ?>
          </span>
        </div>
        <div class="card-body">
          <div class="card-title">
            <h3><?php echo htmlspecialchars($internship['title']); ?></h3>
            <p><?php echo htmlspecialchars($internship['company']); ?> • <?php echo htmlspecialchars($internship['location']); ?></p>
          </div>
          <div class="card-meta">
            <span class="meta-tag">🗂️ <?php echo htmlspecialchars($internship['field']); ?></span>
            <span class="meta-tag">⏱️ <?php echo $internship['duration']; ?> months</span>
            <span class="meta-tag">📅 Deadline: <?php echo $deadline->format('d M Y'); ?></span>
          </div>
          <p class="card-desc"><?php echo htmlspecialchars(substr($internship['description'], 0, 120)) . '...'; ?></p>
        </div>
        <div class="card-footer">
          <span style="font-size:.75rem;color:var(--grey-400)">Slots: <?php echo $internship['slots']; ?></span>
          <a href="/internhub/detail.php?id=<?php echo $internship['id']; ?>" class="btn-apply">View Details →</a>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>

<footer class="footer">
  <div class="footer-inner">
    <span class="footer-brand">Digital Internship Portal</span>
    <span>© 2025 · UICT Nakawa · Built by Otuura Brian Oneka & Team</span>
  </div>
</footer>

</body>
</html>