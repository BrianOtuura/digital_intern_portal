<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'student') {
    header('Location: /internhub/auth/login.php');
    exit;
}
require_once 'includes/db.php';
$userId    = $_SESSION['user_id'];
$userName  = $_SESSION['user_name'] ?? '';
$firstName = explode(' ', $userName)[0];

$stmt = $pdo->prepare("
    SELECT a.*, i.title, i.company, i.deadline, i.location,
           i.logo, i.logo_color, i.field, i.duration, i.id AS internship_id
    FROM applications a
    JOIN internships i ON a.listing_id = i.id
    WHERE a.student_id = ?
    ORDER BY a.created_at DESC
");
$stmt->execute([$userId]);
$applications = $stmt->fetchAll();

$total       = count($applications);
$pending     = count(array_filter($applications, fn($a) => $a['status']==='pending'));
$reviewed    = count(array_filter($applications, fn($a) => $a['status']==='reviewed'));
$shortlisted = count(array_filter($applications, fn($a) => $a['status']==='shortlisted'));
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>My Applications — Digital Internship Portal</title>
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
  --blue:#1D4ED8;--blue-bg:#EFF6FF;--blue-border:#BFDBFE;
  --shadow-sm:0 1px 4px rgba(6,46,34,.07);
  --shadow-md:0 4px 20px rgba(6,46,34,.10);
  --shadow-lg:0 12px 48px rgba(6,46,34,.13);
  --r-sm:6px;--r-md:12px;--r-lg:20px;--r-xl:28px;
  --ease:cubic-bezier(.4,0,.2,1);--t:.2s;
}
html{scroll-behavior:smooth}
body{font-family:'Plus Jakarta Sans',sans-serif;background:var(--off);color:var(--ink);min-height:100vh;-webkit-font-smoothing:antialiased}
a{text-decoration:none;color:inherit}
::-webkit-scrollbar{width:5px}
::-webkit-scrollbar-track{background:var(--off)}
::-webkit-scrollbar-thumb{background:var(--grey-200);border-radius:99px}

/* SIDEBAR — identical to dashboard */
.sidebar{position:fixed;top:0;left:0;width:240px;height:100vh;background:var(--teal-900);display:flex;flex-direction:column;z-index:100;border-right:1px solid rgba(255,255,255,.07)}
.sidebar::before{content:'';position:absolute;bottom:0;left:0;right:0;height:35%;background:radial-gradient(ellipse 80% 60% at 20% 100%,rgba(18,138,105,.2) 0%,transparent 65%);pointer-events:none}
.sb-brand{padding:1.3rem;border-bottom:1px solid rgba(255,255,255,.07);display:flex;align-items:center;gap:9px;position:relative;z-index:1}
.sb-brand-dot{width:8px;height:8px;border-radius:50%;background:var(--teal-400);box-shadow:0 0 0 3px rgba(29,184,133,.2);flex-shrink:0}
.sb-brand-name{font-family:'Fraunces',serif;font-size:.95rem;font-weight:700;color:#fff;line-height:1.2}
.sb-brand-sub{font-size:.67rem;color:rgba(255,255,255,.3);margin-top:1px}
.sb-user{padding:.95rem 1.2rem;border-bottom:1px solid rgba(255,255,255,.07);display:flex;align-items:center;gap:.8rem;position:relative;z-index:1}
.sb-avatar{width:36px;height:36px;border-radius:50%;background:var(--teal-700);border:2px solid rgba(29,184,133,.3);display:flex;align-items:center;justify-content:center;font-family:'Fraunces',serif;font-weight:700;font-size:.88rem;color:var(--teal-400);flex-shrink:0}
.sb-uname{font-size:.84rem;font-weight:600;color:#fff;line-height:1.2}
.sb-pill{display:inline-flex;align-items:center;gap:3px;font-size:.63rem;font-weight:700;padding:2px 7px;border-radius:99px;margin-top:3px;background:rgba(29,184,133,.15);color:var(--teal-400);border:1px solid rgba(29,184,133,.25)}
.sb-nav{flex:1;padding:.9rem .75rem;display:flex;flex-direction:column;gap:.15rem;overflow-y:auto;position:relative;z-index:1}
.sb-sec{font-size:.62rem;font-weight:700;text-transform:uppercase;letter-spacing:.12em;color:rgba(255,255,255,.2);padding:.75rem .8rem .25rem;margin-top:.3rem}
.sb-item{display:flex;align-items:center;gap:.8rem;padding:.62rem .82rem;border-radius:var(--r-md);font-size:.83rem;font-weight:500;color:rgba(255,255,255,.48);transition:all var(--t) var(--ease)}
.sb-item:hover{background:rgba(255,255,255,.06);color:rgba(255,255,255,.85)}
.sb-item.active{background:rgba(29,184,133,.12);color:var(--teal-400);font-weight:600}
.sb-item-ico{font-size:.92rem;width:18px;text-align:center;flex-shrink:0}
.sb-count{margin-left:auto;background:rgba(255,255,255,.1);color:rgba(255,255,255,.6);font-size:.65rem;font-weight:700;padding:1px 7px;border-radius:99px}
.sb-foot{padding:.85rem .75rem;border-top:1px solid rgba(255,255,255,.07);position:relative;z-index:1}
.sb-logout{display:flex;align-items:center;gap:.75rem;padding:.62rem .82rem;border-radius:var(--r-md);font-size:.82rem;font-weight:500;color:rgba(255,100,100,.55);transition:all var(--t) var(--ease)}
.sb-logout:hover{background:rgba(248,113,113,.08);color:rgba(255,120,120,.9)}

/* MAIN */
.main{margin-left:240px;min-height:100vh;display:flex;flex-direction:column}
.topbar{height:58px;background:var(--white);border-bottom:1px solid var(--grey-100);display:flex;align-items:center;justify-content:space-between;padding:0 1.8rem;position:sticky;top:0;z-index:50;box-shadow:var(--shadow-sm)}
.topbar-left{display:flex;align-items:center;gap:.75rem}
.topbar-title{font-family:'Fraunces',serif;font-size:.98rem;font-weight:700;color:var(--ink)}
.topbar-count{font-size:.75rem;font-weight:600;background:var(--teal-50);color:var(--teal-700);border:1px solid var(--teal-100);padding:3px 10px;border-radius:99px}
.topbar-right{display:flex;align-items:center;gap:.65rem}
.tb-btn{display:inline-flex;align-items:center;gap:6px;font-size:.82rem;font-weight:600;padding:7px 15px;border-radius:var(--r-sm);transition:all var(--t) var(--ease);font-family:'Plus Jakarta Sans',sans-serif}
.tb-teal{background:var(--teal-800);color:#fff;border:none;box-shadow:0 2px 8px rgba(11,77,59,.22)}
.tb-teal:hover{background:var(--teal-900)}
.tb-ghost{background:transparent;color:var(--grey-600);border:1px solid var(--grey-200)}
.tb-ghost:hover{background:var(--off);color:var(--ink)}

.content{flex:1;padding:1.8rem}

/* PAGE HEADER */
.page-header{margin-bottom:1.8rem}
.page-header-eyebrow{font-size:.68rem;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:var(--teal-600);margin-bottom:.4rem}
.page-header-title{font-family:'Fraunces',serif;font-size:clamp(1.4rem,2.5vw,2rem);font-weight:700;color:var(--ink);margin-bottom:.35rem}
.page-header-sub{font-size:.88rem;color:var(--grey-600);line-height:1.6}

/* STATS */
.stats{display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;margin-bottom:1.8rem}
.stat-card{background:var(--white);border:1px solid var(--grey-100);border-radius:var(--r-lg);padding:1.2rem 1.3rem;transition:all var(--t) var(--ease)}
.stat-card:hover{box-shadow:var(--shadow-md);border-color:var(--grey-200)}
.stat-ico{font-size:1.2rem;margin-bottom:.55rem}
.stat-num{font-family:'Fraunces',serif;font-size:1.9rem;font-weight:700;line-height:1;margin-bottom:3px}
.stat-lbl{font-size:.73rem;color:var(--grey-600)}

/* EMPTY STATE */
.empty-state{background:var(--white);border:1px dashed var(--grey-200);border-radius:var(--r-xl);padding:4rem;text-align:center}
.empty-ico{font-size:3rem;margin-bottom:1rem;display:block;opacity:.5}
.empty-title{font-family:'Fraunces',serif;font-weight:700;font-size:1.3rem;color:var(--ink);margin-bottom:.5rem}
.empty-body{font-size:.9rem;color:var(--grey-400);max-width:360px;margin:0 auto 1.5rem;line-height:1.65}
.btn-teal{display:inline-flex;align-items:center;gap:7px;background:var(--teal-800);color:#fff;font-family:'Plus Jakarta Sans',sans-serif;font-weight:600;font-size:.9rem;padding:11px 22px;border-radius:var(--r-sm);border:none;cursor:pointer;box-shadow:0 2px 8px rgba(11,77,59,.22);transition:all var(--t) var(--ease)}
.btn-teal:hover{background:var(--teal-900);transform:translateY(-1px)}

/* APPLICATION CARDS */
.app-list{display:flex;flex-direction:column;gap:1rem}
.app-card{background:var(--white);border:1px solid var(--grey-100);border-radius:var(--r-xl);overflow:hidden;transition:all var(--t) var(--ease)}
.app-card:hover{box-shadow:var(--shadow-md);border-color:var(--grey-200);transform:translateY(-2px)}

/* Card top stripe by status */
.app-card-stripe{height:3px}
.stripe-pending{background:linear-gradient(90deg,var(--amber),#FCD34D)}
.stripe-reviewed{background:linear-gradient(90deg,var(--blue),#60A5FA)}
.stripe-shortlisted{background:linear-gradient(90deg,var(--green),#4ADE80)}
.stripe-rejected{background:linear-gradient(90deg,var(--red),#F87171)}

.app-card-body{padding:1.4rem 1.6rem;display:grid;grid-template-columns:auto 1fr auto;gap:1.2rem;align-items:start}
.app-logo{width:48px;height:48px;border-radius:var(--r-md);flex-shrink:0;display:flex;align-items:center;justify-content:center;font-family:'Fraunces',serif;font-weight:700;font-size:.95rem;color:#fff;box-shadow:var(--shadow-sm)}
.app-info{min-width:0}
.app-company{font-size:.78rem;color:var(--grey-400);font-weight:500;margin-bottom:2px}
.app-title{font-family:'Fraunces',serif;font-weight:700;font-size:1.05rem;color:var(--ink);margin-bottom:.5rem;line-height:1.25}
.app-meta-row{display:flex;gap:1.2rem;flex-wrap:wrap;margin-bottom:.6rem}
.app-meta{display:flex;align-items:center;gap:.4rem;font-size:.78rem;color:var(--grey-600)}
.app-meta-ico{font-size:.85rem}
.app-status-col{display:flex;flex-direction:column;align-items:flex-end;gap:.6rem;flex-shrink:0}
.status-badge{display:inline-flex;align-items:center;gap:5px;font-size:.75rem;font-weight:700;padding:5px 12px;border-radius:99px}
.status-badge::before{content:'';width:6px;height:6px;border-radius:50%;flex-shrink:0}
.s-pending{background:var(--amber-bg);color:var(--amber);border:1px solid var(--amber-border)}
.s-pending::before{background:var(--amber)}
.s-reviewed{background:var(--blue-bg);color:var(--blue);border:1px solid var(--blue-border)}
.s-reviewed::before{background:var(--blue)}
.s-shortlisted{background:var(--green-bg);color:var(--green);border:1px solid var(--green-border)}
.s-shortlisted::before{background:var(--green)}
.s-rejected{background:var(--red-bg);color:var(--red);border:1px solid var(--red-border)}
.s-rejected::before{background:var(--red)}
.app-date{font-size:.73rem;color:var(--grey-400)}
.app-view-btn{display:inline-flex;align-items:center;gap:5px;font-size:.76rem;font-weight:600;color:var(--teal-700);padding:5px 12px;border-radius:var(--r-sm);border:1px solid var(--teal-200);background:var(--teal-50);transition:all var(--t) var(--ease)}
.app-view-btn:hover{background:var(--teal-100);border-color:var(--teal-400)}

/* COVER LETTER TOGGLE */
.app-cover{border-top:1px solid var(--grey-100);padding:1rem 1.6rem;background:var(--off)}
.app-cover-label{font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--grey-400);margin-bottom:.4rem}
.app-cover-text{font-size:.85rem;color:var(--grey-600);line-height:1.7;font-style:italic}
.app-cover-empty{font-size:.83rem;color:var(--grey-400);font-style:italic}

/* STATUS GUIDE */
.status-guide{background:var(--white);border:1px solid var(--grey-100);border-radius:var(--r-xl);padding:1.5rem;margin-bottom:1.8rem}
.sg-title{font-family:'Fraunces',serif;font-weight:700;font-size:.95rem;color:var(--ink);margin-bottom:1rem}
.sg-items{display:grid;grid-template-columns:repeat(4,1fr);gap:.75rem}
.sg-item{display:flex;align-items:flex-start;gap:.7rem;padding:.8rem;background:var(--off);border-radius:var(--r-md)}
.sg-dot{width:10px;height:10px;border-radius:50%;flex-shrink:0;margin-top:3px}
.sg-name{font-size:.8rem;font-weight:700;color:var(--ink);margin-bottom:2px}
.sg-desc{font-size:.72rem;color:var(--grey-600);line-height:1.45}

/* FOOTER */
.footer{background:#040F0A;padding:2.5rem 0 2rem;margin-top:3rem}
.footer-inner{max-width:100%;padding:0 2rem;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem;font-size:.78rem;color:rgba(255,255,255,.22)}
.footer-brand{font-family:'Fraunces',serif;font-size:.9rem;font-weight:700;color:rgba(255,255,255,.5)}

/* RESPONSIVE */
@media(max-width:1024px){
  .stats{grid-template-columns:repeat(2,1fr)}
  .sg-items{grid-template-columns:repeat(2,1fr)}
}
@media(max-width:768px){
  .sidebar{display:none}
  .main{margin-left:0}
  .content{padding:1.1rem}
  .stats{grid-template-columns:repeat(2,1fr)}
  .app-card-body{grid-template-columns:auto 1fr;gap:.9rem}
  .app-status-col{grid-column:1/-1;flex-direction:row;align-items:center;justify-content:flex-start}
  .sg-items{grid-template-columns:1fr 1fr}
}
</style>
</head>
<body>

<!-- SIDEBAR -->
<aside class="sidebar">
  <div class="sb-brand">
    <span class="sb-brand-dot"></span>
    <div>
      <div class="sb-brand-name">Digital Internship Portal</div>
      <div class="sb-brand-sub">UICT Nakawa · Kampala</div>
    </div>
  </div>
  <div class="sb-user">
    <div class="sb-avatar"><?php echo strtoupper(substr($firstName,0,1)); ?></div>
    <div>
      <div class="sb-uname"><?php echo htmlspecialchars($firstName); ?></div>
      <div class="sb-pill">🎓 Student</div>
    </div>
  </div>
  <nav class="sb-nav">
    <div class="sb-sec">Student</div>
    <a href="/internhub/auth/dashboard.php" class="sb-item"><span class="sb-item-ico">🏠</span>Dashboard</a>
    <a href="/internhub/listings.php" class="sb-item"><span class="sb-item-ico">💼</span>Browse Internships</a>
    <a href="/internhub/my-applications.php" class="sb-item active">
      <span class="sb-item-ico">📋</span>My Applications
      <?php if($total>0): ?><span class="sb-count"><?php echo $total; ?></span><?php endif; ?>
    </a>
    <a href="/internhub/readiness.php" class="sb-item"><span class="sb-item-ico">🎓</span>Readiness Program</a>
    <div class="sb-sec">Platform</div>
    <a href="/internhub/about.php" class="sb-item"><span class="sb-item-ico">ℹ️</span>About DIP</a>
    <a href="/internhub/contact.php" class="sb-item"><span class="sb-item-ico">📬</span>Contact Us</a>
  </nav>
  <div class="sb-foot">
    <a href="/internhub/auth/actions/logout.php" class="sb-logout"><span>🚪</span>Sign Out</a>
  </div>
</aside>

<!-- MAIN -->
<div class="main">

  <div class="topbar">
    <div class="topbar-left">
      <div class="topbar-title">My Applications</div>
      <?php if($total>0): ?>
        <span class="topbar-count"><?php echo $total; ?> total</span>
      <?php endif; ?>
    </div>
    <div class="topbar-right">
      <a href="/internhub/auth/dashboard.php" class="tb-btn tb-ghost">← Dashboard</a>
      <a href="/internhub/listings.php" class="tb-btn tb-teal">Browse More →</a>
    </div>
  </div>

  <div class="content">

    <div class="page-header">
      <div class="page-header-eyebrow">📋 Application Tracker</div>
      <h1 class="page-header-title">Your Internship Applications</h1>
      <p class="page-header-sub">Track every application you've submitted through the portal. Companies will contact you directly via email if shortlisted.</p>
    </div>

    <!-- STATS -->
    <div class="stats">
      <div class="stat-card">
        <div class="stat-ico">📋</div>
        <div class="stat-num" style="color:var(--ink)"><?php echo $total; ?></div>
        <div class="stat-lbl">Total applications</div>
      </div>
      <div class="stat-card">
        <div class="stat-ico">⏳</div>
        <div class="stat-num" style="color:var(--amber)"><?php echo $pending; ?></div>
        <div class="stat-lbl">Awaiting review</div>
      </div>
      <div class="stat-card">
        <div class="stat-ico">👀</div>
        <div class="stat-num" style="color:var(--blue)"><?php echo $reviewed; ?></div>
        <div class="stat-lbl">Reviewed</div>
      </div>
      <div class="stat-card">
        <div class="stat-ico">⭐</div>
        <div class="stat-num" style="color:var(--green)"><?php echo $shortlisted; ?></div>
        <div class="stat-lbl">Shortlisted</div>
      </div>
    </div>

    <!-- STATUS GUIDE -->
    <div class="status-guide">
      <div class="sg-title">What each status means</div>
      <div class="sg-items">
        <div class="sg-item">
          <div class="sg-dot" style="background:var(--amber)"></div>
          <div><div class="sg-name">Pending</div><div class="sg-desc">Application received. Company has not yet reviewed it.</div></div>
        </div>
        <div class="sg-item">
          <div class="sg-dot" style="background:var(--blue)"></div>
          <div><div class="sg-name">Reviewed</div><div class="sg-desc">Company has seen your application and is considering it.</div></div>
        </div>
        <div class="sg-item">
          <div class="sg-dot" style="background:var(--green)"></div>
          <div><div class="sg-name">Shortlisted</div><div class="sg-desc">You made the shortlist. Expect a direct email from the company soon.</div></div>
        </div>
        <div class="sg-item">
          <div class="sg-dot" style="background:var(--red)"></div>
          <div><div class="sg-name">Not Selected</div><div class="sg-desc">This application was not taken forward. Keep applying — more listings are added regularly.</div></div>
        </div>
      </div>
    </div>

    <!-- PLACEMENTS SECTION - ADD THIS -->
<?php
// Get confirmed placements for this student
$placements = $pdo->prepare("
    SELECT p.*, i.title, i.company, i.location, i.duration 
    FROM placements p 
    JOIN internships i ON p.internship_id = i.id 
    WHERE p.student_id = :student_id AND p.admin_confirmed = 1
    ORDER BY p.confirmation_date DESC
");
$placements->execute([':student_id' => $userId]);
$myPlacements = $placements->fetchAll();
?>

<?php if(!empty($myPlacements)): ?>
<div class="card" style="margin-bottom:1.5rem;border-left:4px solid #16A34A">
  <div class="card-head" style="padding:1.2rem 1.5rem;background:#F0FDF4">
    <div class="card-title" style="font-size:1rem">✅ Confirmed Placements</div>
  </div>
  <div class="card-body">
    <?php foreach($myPlacements as $p): ?>
    <div class="lst-row" style="border-bottom:1px solid #EEF1EF;padding:1rem 0">
      <div>
        <div class="lst-title" style="font-weight:700"><?php echo htmlspecialchars($p['title']); ?></div>
        <div class="lst-meta"><?php echo htmlspecialchars($p['company']); ?> · <?php echo htmlspecialchars($p['location']); ?> · <?php echo $p['duration']; ?> months</div>
        <div class="lst-meta" style="color:#16A34A;margin-top:5px">✓ Placement confirmed on <?php echo date('d M Y', strtotime($p['confirmation_date'])); ?></div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</div>
<?php endif; ?>

    <!-- APPLICATION LIST -->
    <?php if(empty($applications)): ?>
      <div class="empty-state">
        <span class="empty-ico">📭</span>
        <div class="empty-title">No applications yet</div>
        <p class="empty-body">You haven't applied to any internships through the portal yet. Browse open opportunities and submit your first application.</p>
        <a href="/internhub/listings.php" class="btn-teal">Browse Internships →</a>
      </div>

    <?php else: ?>
      <div class="app-list">
        <?php foreach($applications as $app):
          $dl = new DateTime($app['deadline']);
          $appliedDate = new DateTime($app['created_at']);
          $daysLeft = (int)(new DateTime())->diff($dl)->format('%r%a');
          $status = $app['status'];
          $stripeClass = 'stripe-'.($status==='not_selected'?'rejected':$status);
          $statusClass = 's-'.($status==='not_selected'?'rejected':$status);
          $statusLabel = match($status) {
            'pending'      => 'Pending Review',
            'reviewed'     => 'Reviewed',
            'shortlisted'  => '⭐ Shortlisted',
            'not_selected' => 'Not Selected',
            default        => ucfirst($status)
          };
        ?>
        <div class="app-card">
          <div class="app-card-stripe <?php echo $stripeClass; ?>"></div>
          <div class="app-card-body">

            <!-- LOGO -->
            <div class="app-logo" style="background:<?php echo htmlspecialchars($app['logo_color']); ?>">
              <?php echo htmlspecialchars($app['logo']); ?>
            </div>

            <!-- INFO -->
            <div class="app-info">
              <div class="app-company"><?php echo htmlspecialchars($app['company']); ?></div>
              <div class="app-title"><?php echo htmlspecialchars($app['title']); ?></div>
              <div class="app-meta-row">
                <div class="app-meta">
                  <span class="app-meta-ico">📍</span>
                  <?php echo htmlspecialchars($app['location'] ?? 'Kampala'); ?>
                </div>
                <div class="app-meta">
                  <span class="app-meta-ico">🗂️</span>
                  <?php echo htmlspecialchars($app['field'] ?? '—'); ?>
                </div>
                <div class="app-meta">
                  <span class="app-meta-ico">⏱️</span>
                  <?php echo htmlspecialchars($app['duration']); ?> months
                </div>
                <div class="app-meta">
                  <span class="app-meta-ico">📅</span>
                  Deadline: <?php echo $dl->format('d M Y'); ?>
                  <?php if($daysLeft<0): ?><span style="color:var(--red);margin-left:3px">(closed)</span><?php elseif($daysLeft<=7): ?><span style="color:var(--amber);margin-left:3px">(<?php echo $daysLeft; ?>d left)</span><?php endif; ?>
                </div>
              </div>
            </div>

            <!-- STATUS COL -->
            <div class="app-status-col">
              <span class="status-badge <?php echo $statusClass; ?>"><?php echo $statusLabel; ?></span>
              <div class="app-date">Applied <?php echo $appliedDate->format('d M Y'); ?></div>
              <a href="/internhub/detail.php?id=<?php echo $app['internship_id']; ?>" class="app-view-btn">
                View Listing →
              </a>
            </div>

          </div>

          <!-- COVER LETTER -->
          <?php if(!empty($app['cover_letter'])): ?>
          <div class="app-cover">
            <div class="app-cover-label">Your Cover Letter</div>
            <div class="app-cover-text">"<?php echo nl2br(htmlspecialchars($app['cover_letter'])); ?>"</div>
          </div>
          <?php else: ?>
          <div class="app-cover">
            <div class="app-cover-empty">No cover letter submitted with this application.</div>
          </div>
          <?php endif; ?>

        </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

  </div>
</div>

<footer class="footer">
  <div class="footer-inner">
    <span class="footer-brand">Digital Internship Portal</span>
    <span>© 2025 · UICT Nakawa · Built by Otuura Brian Oneka & Team</span>
    <a href="/internhub/index.php" style="color:rgba(255,255,255,.4);font-size:.78rem">← Back to site</a>
  </div>
</footer>

</body>
</html>