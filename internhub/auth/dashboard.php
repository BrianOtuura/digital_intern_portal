<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: /internhub/auth/login.php?error=' . urlencode('Please log in to access your dashboard.'));
    exit;
}

require_once '../includes/db.php';

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

$userId   = $_SESSION['user_id'];
$role     = $_SESSION['user_role'];

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
$stmt->execute([':id' => $userId]);
$user = $stmt->fetch();

$firstName = explode(' ', $user['full_name'] ?? $user['company_name'] ?? 'User')[0];

if ($role === 'student') {
    $recentListings = $pdo->query("SELECT * FROM internships WHERE status='approved' ORDER BY created_at DESC LIMIT 6")->fetchAll();
    $totalOpen      = (int)$pdo->query("SELECT COUNT(*) FROM internships WHERE status='approved' AND deadline >= CURDATE()")->fetchColumn();

    try {
        $enrStmt = $pdo->prepare("SELECT * FROM readiness_enrollments WHERE user_id = :id LIMIT 1");
        $enrStmt->execute([':id' => $userId]);
        $enrollment = $enrStmt->fetch();
    } catch(Exception $e) { 
        $enrollment = null; 
    }

} else {
    $stmt = $pdo->prepare("SELECT * FROM internships WHERE posted_by = :id ORDER BY created_at DESC");
    $stmt->execute([':id' => $userId]);
    $myListings     = $stmt->fetchAll();
    $myTotal        = count($myListings);
    $myApproved     = count(array_filter($myListings, fn($l) => $l['status']==='approved'));
    $myPending      = count(array_filter($myListings, fn($l) => $l['status']==='pending'));
    $myRejected     = count(array_filter($myListings, fn($l) => $l['status']==='rejected'));
    $hasUsedFreeTrial = $myTotal > 0;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Dashboard — Digital Internship Portal</title>
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

.b-closed{background:#FEF2F2;color:#DC2626;border:1px solid #FECACA}
.b-closing{background:#FFFBEB;color:#B45309;border:1px solid #FDE68A}
.b-open{background:#F0FDF4;color:#16A34A;border:1px solid #BBF7D0}
.b-placed{background:#DCFCE7;color:#166534;border:1px solid #86EFAC}

.sidebar{position:fixed;top:0;left:0;width:240px;height:100vh;background:var(--teal-900);display:flex;flex-direction:column;z-index:100;border-right:1px solid rgba(255,255,255,.07)}
.sidebar::before{content:'';position:absolute;bottom:0;left:0;right:0;height:35%;background:radial-gradient(ellipse 80% 60% at 20% 100%,rgba(18,138,105,.2) 0%,transparent 65%);pointer-events:none}
.sb-brand{padding:1.3rem 1.3rem;border-bottom:1px solid rgba(255,255,255,.07);display:flex;align-items:center;gap:9px;position:relative;z-index:1}
.sb-brand-dot{width:8px;height:8px;border-radius:50%;background:var(--teal-400);box-shadow:0 0 0 3px rgba(29,184,133,.2);flex-shrink:0}
.sb-brand-name{font-family:'Fraunces',serif;font-size:.95rem;font-weight:700;color:#fff;line-height:1.2}
.sb-brand-sub{font-size:.67rem;color:rgba(255,255,255,.3);margin-top:1px}
.sb-user{padding:.95rem 1.2rem;border-bottom:1px solid rgba(255,255,255,.07);display:flex;align-items:center;gap:.8rem;position:relative;z-index:1}
.sb-avatar{width:36px;height:36px;border-radius:50%;background:var(--teal-700);border:2px solid rgba(29,184,133,.3);display:flex;align-items:center;justify-content:center;font-family:'Fraunces',serif;font-weight:700;font-size:.88rem;color:var(--teal-400);flex-shrink:0}
.sb-uname{font-size:.84rem;font-weight:600;color:#fff;line-height:1.2}
.sb-pill{display:inline-flex;align-items:center;gap:3px;font-size:.63rem;font-weight:700;padding:2px 7px;border-radius:99px;margin-top:3px}
.pill-s{background:rgba(29,184,133,.15);color:var(--teal-400);border:1px solid rgba(29,184,133,.25)}
.pill-c{background:rgba(212,160,23,.15);color:var(--gold);border:1px solid rgba(212,160,23,.25)}
.sb-nav{flex:1;padding:.9rem .75rem;display:flex;flex-direction:column;gap:.15rem;overflow-y:auto;position:relative;z-index:1}
.sb-sec{font-size:.62rem;font-weight:700;text-transform:uppercase;letter-spacing:.12em;color:rgba(255,255,255,.2);padding:.75rem .8rem .25rem;margin-top:.3rem}
.sb-item{display:flex;align-items:center;gap:.8rem;padding:.62rem .82rem;border-radius:var(--r-md);font-size:.83rem;font-weight:500;color:rgba(255,255,255,.48);transition:all var(--t) var(--ease)}
.sb-item:hover{background:rgba(255,255,255,.06);color:rgba(255,255,255,.85)}
.sb-item.active{background:rgba(29,184,133,.12);color:var(--teal-400);font-weight:600}
.sb-item-ico{font-size:.92rem;width:18px;text-align:center;flex-shrink:0}
.sb-badge{margin-left:auto;background:var(--gold);color:var(--ink);font-size:.6rem;font-weight:700;padding:1px 6px;border-radius:99px}
.sb-foot{padding:.85rem .75rem;border-top:1px solid rgba(255,255,255,.07);position:relative;z-index:1}
.sb-logout{display:flex;align-items:center;gap:.75rem;padding:.62rem .82rem;border-radius:var(--r-md);font-size:.82rem;font-weight:500;color:rgba(255,100,100,.55);transition:all var(--t) var(--ease)}
.sb-logout:hover{background:rgba(248,113,113,.08);color:rgba(255,120,120,.9)}

.main{margin-left:240px;min-height:100vh;display:flex;flex-direction:column}
.topbar{height:58px;background:var(--white);border-bottom:1px solid var(--grey-100);display:flex;align-items:center;justify-content:space-between;padding:0 1.8rem;position:sticky;top:0;z-index:50;box-shadow:var(--shadow-sm)}
.topbar-greet{font-family:'Fraunces',serif;font-size:.98rem;font-weight:700;color:var(--ink)}
.topbar-greet span{color:var(--teal-700)}
.topbar-right{display:flex;align-items:center;gap:.65rem}
.tb-btn{display:inline-flex;align-items:center;gap:6px;font-size:.82rem;font-weight:600;padding:7px 15px;border-radius:var(--r-sm);transition:all var(--t) var(--ease);font-family:'Plus Jakarta Sans',sans-serif;cursor:pointer}
.tb-teal{background:var(--teal-800);color:#fff;border:none;box-shadow:0 2px 8px rgba(11,77,59,.22)}
.tb-teal:hover{background:var(--teal-900)}
.tb-ghost{background:transparent;color:var(--grey-600);border:1px solid var(--grey-200)}
.tb-ghost:hover{background:var(--off);color:var(--ink)}

.content{flex:1;padding:1.8rem}
.wb{background:var(--teal-800);border-radius:var(--r-xl);padding:1.8rem 2.2rem;display:grid;grid-template-columns:1fr auto;gap:2rem;align-items:center;margin-bottom:1.8rem;position:relative;overflow:hidden}
.wb::before{content:'';position:absolute;top:-30%;right:5%;width:260px;height:260px;border-radius:50%;background:radial-gradient(circle,rgba(29,184,133,.2),transparent 65%);pointer-events:none}
.wb-ey{font-size:.67rem;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:var(--teal-400);margin-bottom:.35rem;position:relative;z-index:1}
.wb-title{font-family:'Fraunces',serif;font-size:clamp(1.2rem,2.3vw,1.7rem);font-weight:700;color:#fff;line-height:1.15;margin-bottom:.4rem;position:relative;z-index:1}
.wb-body{font-size:.86rem;color:rgba(255,255,255,.58);line-height:1.65;position:relative;z-index:1;max-width:460px}
.wb-actions{display:flex;flex-direction:column;gap:.5rem;flex-shrink:0;position:relative;z-index:1}
.btn-gold{display:inline-flex;align-items:center;gap:6px;background:var(--gold);color:var(--ink);font-family:'Plus Jakarta Sans',sans-serif;font-weight:700;font-size:.84rem;padding:10px 18px;border-radius:var(--r-sm);border:none;cursor:pointer;box-shadow:0 3px 12px rgba(212,160,23,.28);transition:all var(--t) var(--ease);white-space:nowrap}
.btn-gold:hover{background:var(--gold-dk);transform:translateY(-1px)}
.btn-wl{display:inline-flex;align-items:center;gap:6px;background:rgba(255,255,255,.1);color:#fff;font-family:'Plus Jakarta Sans',sans-serif;font-weight:500;font-size:.84rem;padding:10px 18px;border-radius:var(--r-sm);border:1px solid rgba(255,255,255,.18);transition:all var(--t) var(--ease);white-space:nowrap}
.btn-wl:hover{background:rgba(255,255,255,.17)}

.stats{display:grid;gap:1rem;margin-bottom:1.8rem}
.stats-4{grid-template-columns:repeat(4,1fr)}
.stat-card{background:var(--white);border:1px solid var(--grey-100);border-radius:var(--r-lg);padding:1.2rem 1.3rem;transition:all var(--t) var(--ease)}
.stat-card:hover{box-shadow:var(--shadow-md);border-color:var(--grey-200);transform:translateY(-2px)}
.stat-ico{font-size:1.2rem;margin-bottom:.55rem}
.stat-num{font-family:'Fraunces',serif;font-size:1.9rem;font-weight:700;line-height:1;margin-bottom:3px}
.stat-lbl{font-size:.73rem;color:var(--grey-600)}

.dash-grid{display:grid;grid-template-columns:1fr 300px;gap:1.4rem;align-items:start}
.card{background:var(--white);border:1px solid var(--grey-100);border-radius:var(--r-xl);overflow:hidden;margin-bottom:1.4rem}
.card:last-child{margin-bottom:0}
.card-head{padding:1.2rem 1.5rem;border-bottom:1px solid var(--grey-100);display:flex;align-items:center;justify-content:space-between}
.card-title{font-family:'Fraunces',serif;font-weight:700;font-size:.98rem;color:var(--ink)}
.card-lnk{font-size:.77rem;font-weight:600;color:var(--teal-700)}
.card-lnk:hover{color:var(--teal-900)}
.card-body{padding:1.3rem 1.5rem}
.card-empty{padding:2.5rem;text-align:center;color:var(--grey-400);font-size:.87rem}
.card-empty-ico{font-size:2rem;margin-bottom:.5rem;display:block;opacity:.5}

.lst-row{display:flex;align-items:center;gap:.9rem;padding:.85rem 0;border-bottom:1px solid var(--grey-100)}
.lst-row:last-child{border-bottom:none;padding-bottom:0}
.lst-logo{width:36px;height:36px;border-radius:var(--r-sm);flex-shrink:0;display:flex;align-items:center;justify-content:center;font-family:'Fraunces',serif;font-weight:700;font-size:.76rem;color:#fff}
.lst-info{flex:1;min-width:0}
.lst-title{font-family:'Fraunces',serif;font-weight:600;font-size:.88rem;color:var(--ink);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;margin-bottom:2px}
.lst-meta{font-size:.73rem;color:var(--grey-400)}
.lst-badge{font-size:.64rem;font-weight:700;padding:2px 8px;border-radius:99px;flex-shrink:0}
.lst-cta{font-size:.74rem;font-weight:600;color:var(--teal-700);flex-shrink:0}
.lst-cta:hover{color:var(--teal-900)}

.profile-card{background:var(--white);border:1px solid var(--grey-100);border-radius:var(--r-xl);overflow:hidden;margin-bottom:1.4rem}
.profile-head{padding:1.3rem;text-align:center;border-bottom:1px solid var(--grey-100)}
.profile-av{width:60px;height:60px;border-radius:50%;background:var(--teal-800);display:flex;align-items:center;justify-content:center;font-family:'Fraunces',serif;font-weight:700;font-size:1.3rem;color:var(--teal-400);margin:0 auto .8rem;box-shadow:var(--shadow-md)}
.profile-name{font-family:'Fraunces',serif;font-weight:700;font-size:1rem;color:var(--ink);margin-bottom:.2rem}
.profile-email{font-size:.75rem;color:var(--grey-400)}
.profile-rows{display:flex;flex-direction:column}
.profile-row{display:flex;justify-content:space-between;align-items:center;padding:.72rem 1.3rem;border-bottom:1px solid var(--grey-100);font-size:.8rem}
.profile-row:last-child{border-bottom:none}
.pr-key{color:var(--grey-400)}
.pr-val{color:var(--ink);font-weight:600;text-align:right;max-width:55%}

.rc-card{background:linear-gradient(135deg,var(--gold-lt),#fff8e6);border:1px solid rgba(212,160,23,.2);border-radius:var(--r-xl);padding:1.5rem;margin-bottom:1.4rem}
.rc-ico{font-size:1.8rem;margin-bottom:.7rem}
.rc-title{font-family:'Fraunces',serif;font-weight:700;font-size:1rem;color:var(--ink);margin-bottom:.35rem}
.rc-body{font-size:.82rem;color:var(--grey-600);line-height:1.65;margin-bottom:1.1rem}
.rc-tiers{display:flex;flex-direction:column;gap:.5rem;margin-bottom:1.1rem}
.rc-tier{display:flex;align-items:center;justify-content:space-between;background:var(--white);border:1px solid rgba(212,160,23,.15);border-radius:var(--r-md);padding:.7rem .9rem}
.rc-tier-l{display:flex;align-items:center;gap:.6rem}
.rc-tier-name{font-size:.82rem;font-weight:600;color:var(--ink)}
.rc-tier-desc{font-size:.7rem;color:var(--grey-600);margin-top:1px}
.rc-price{font-family:'Fraunces',serif;font-weight:700;font-size:.92rem;color:var(--gold-dk)}
.rc-price.free{color:var(--green)}
.momo-note{background:var(--white);border:1px solid rgba(212,160,23,.15);border-radius:var(--r-md);padding:.8rem .9rem;font-size:.77rem;color:var(--grey-600);line-height:1.6;margin-bottom:1rem}
.rc-enrolled{background:var(--green-bg);border:1px solid var(--green-border);border-radius:var(--r-md);padding:.8rem 1rem;display:flex;align-items:center;gap:.7rem;margin-bottom:1rem}
.rc-enrolled-txt{font-size:.83rem;color:var(--green);font-weight:600}
.rc-enrolled-sub{font-size:.72rem;color:var(--green);opacity:.75}

.qa-grid{display:grid;grid-template-columns:1fr 1fr;gap:.65rem;margin-bottom:1.4rem}
.qa-btn{display:flex;flex-direction:column;align-items:center;justify-content:center;gap:.35rem;padding:.9rem;background:var(--white);border:1px solid var(--grey-100);border-radius:var(--r-lg);transition:all var(--t) var(--ease);text-align:center}
.qa-btn:hover{border-color:var(--teal-200);background:var(--teal-50);transform:translateY(-2px);box-shadow:var(--shadow-md)}
.qa-ico{font-size:1.3rem}
.qa-lbl{font-size:.73rem;font-weight:600;color:var(--ink)}

.post-cta{background:var(--teal-800);border-radius:var(--r-xl);padding:1.5rem;position:relative;overflow:hidden;margin-bottom:1.4rem}
.post-cta::before{content:'';position:absolute;top:-20%;right:-5%;width:140px;height:140px;border-radius:50%;background:radial-gradient(circle,rgba(29,184,133,.2),transparent 65%);pointer-events:none}
.pc-ey{font-size:.66rem;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:var(--teal-400);margin-bottom:.4rem;position:relative;z-index:1}
.pc-title{font-family:'Fraunces',serif;font-weight:700;font-size:.98rem;color:#fff;margin-bottom:.35rem;position:relative;z-index:1}
.pc-body{font-size:.79rem;color:rgba(255,255,255,.53);line-height:1.6;margin-bottom:1rem;position:relative;z-index:1}
.free-badge{display:inline-flex;align-items:center;gap:4px;background:rgba(34,197,94,.15);border:1px solid rgba(34,197,94,.3);border-radius:99px;padding:2px 9px;font-size:.68rem;font-weight:700;color:#22C55E;margin-bottom:.6rem;position:relative;z-index:1}

.pay-guide{background:var(--white);border:1px solid var(--grey-100);border-radius:var(--r-xl);overflow:hidden}
.pay-guide-head{padding:1.1rem 1.3rem;border-bottom:1px solid var(--grey-100)}
.pay-guide-title{font-family:'Fraunces',serif;font-weight:700;font-size:.93rem;color:var(--ink)}
.pay-guide-body{padding:1.1rem 1.3rem;font-size:.8rem;color:var(--grey-600);line-height:1.7}
.momo-row{display:flex;align-items:center;gap:.65rem;background:var(--off);border-radius:var(--r-sm);padding:.55rem .8rem;margin-bottom:.5rem}

.notice{border-radius:var(--r-md);padding:.85rem 1rem;font-size:.83rem;display:flex;align-items:flex-start;gap:.65rem;margin-bottom:1.4rem}
.n-info{background:var(--teal-50);border:1px solid var(--teal-100);color:var(--teal-700)}
.n-warn{background:var(--amber-bg);border:1px solid var(--amber-border);color:var(--amber)}

.co-table{width:100%;border-collapse:collapse}
.co-table th{padding:10px 14px;text-align:left;font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.09em;color:var(--grey-400);border-bottom:1px solid var(--grey-100);background:var(--off)}
.co-table td{padding:11px 14px;font-size:.84rem;border-bottom:1px solid var(--grey-100);vertical-align:middle}
.co-table tr:last-child td{border-bottom:none}
.co-table tbody tr:hover td{background:var(--off)}
.co-ttitle{font-family:'Fraunces',serif;font-weight:600;font-size:.86rem;color:var(--ink);margin-bottom:2px}
.co-tmeta{font-size:.73rem;color:var(--grey-400)}

.footer{background:#040F0A;padding:3rem 0 2rem;margin-top:3rem}
.footer-inner{max-width:100%;padding:0 2rem;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem;font-size:.78rem;color:rgba(255,255,255,.22)}
.footer-brand{font-family:'Fraunces',serif;font-size:.9rem;font-weight:700;color:rgba(255,255,255,.5)}
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
      <div class="sb-pill <?php echo $role==='student'?'pill-s':'pill-c'; ?>">
        <?php echo $role==='student'?'🎓 Student':'🏢 Company'; ?>
      </div>
    </div>
  </div>
  <nav class="sb-nav">
    <?php if($role==='student'): ?>
      <div class="sb-sec">Student</div>
      <a href="dashboard.php" class="sb-item active"><span class="sb-item-ico">🏠</span>Dashboard</a>
      <a href="/internhub/listings.php" class="sb-item"><span class="sb-item-ico">💼</span>Browse Internships</a>
      <a href="/internhub/readiness.php" class="sb-item"><span class="sb-item-ico">🎓</span>Readiness Program</a>
      <a href="/internhub/my-applications.php" class="sb-item"><span class="sb-item-ico">📋</span>My Applications</a>
    <?php else: ?>
      <div class="sb-sec">Company</div>
      <a href="dashboard.php" class="sb-item active"><span class="sb-item-ico">🏠</span>Dashboard</a>
      <a href="/internhub/company-applications.php" class="sb-item"><span class="sb-item-ico">📥</span>Applications Received</a>
      <a href="/internhub/post.php" class="sb-item">
        <span class="sb-item-ico">➕</span>Post Internship
        <?php if(!$hasUsedFreeTrial): ?><span class="sb-badge">FREE</span><?php endif; ?>
      </a>
      <a href="/internhub/listings.php" class="sb-item"><span class="sb-item-ico">🔍</span>Live Listings</a>
    <?php endif; ?>
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
    <div class="topbar-greet">
      Good <?php echo date('H')<12?'morning':(date('H')<17?'afternoon':'evening'); ?>,
      <span><?php echo htmlspecialchars($firstName); ?></span> 👋
    </div>
    <div class="topbar-right">
      <?php if($role==='student'): ?>
        <a href="/internhub/listings.php" class="tb-btn tb-ghost">Browse Listings</a>
        <a href="/internhub/readiness-enroll.php" class="tb-btn tb-teal">Enroll in Readiness</a>
      <?php else: ?>
        <a href="/internhub/listings.php" class="tb-btn tb-ghost">Live Site</a>
        <a href="/internhub/post.php" class="tb-btn tb-teal">
          <?php echo !$hasUsedFreeTrial?'✨ Post Free':'Post Listing'; ?>
        </a>
      <?php endif; ?>
    </div>
  </div>

  <div class="content">

    <?php if($role === 'student'): ?>
      <!-- STUDENT DASHBOARD -->
      <div class="wb">
        <div>
          <div class="wb-ey">🎓 Student Dashboard</div>
          <h1 class="wb-title"><?php echo $enrollment?'You are Readiness-certified.':'Your next internship is here.'; ?></h1>
          <p class="wb-body">
            <?php if($enrollment): ?>
              Companies with Featured listings can see your certification badge.
            <?php else: ?>
              <strong style="color:#fff"><?php echo $totalOpen; ?> active internships</strong> are available right now.
            <?php endif; ?>
          </p>
        </div>
        <div class="wb-actions">
          <a href="/internhub/listings.php" class="btn-gold">Browse Listings →</a>
        </div>
      </div>

      <!-- MY APPLICATIONS -->
      <div class="card" style="margin-bottom:1.8rem">
        <div class="card-head">
          <div class="card-title">My Applications</div>
          <a href="/internhub/listings.php" class="card-lnk">Browse More Opportunities →</a>
        </div>
        <div class="card-body">
          <?php
          $apps = $pdo->prepare("SELECT a.*, i.title, i.company, i.deadline 
                                FROM applications a 
                                JOIN internships i ON a.listing_id = i.id 
                                WHERE a.student_id = ? 
                                ORDER BY a.created_at DESC LIMIT 10");
          $apps->execute([$userId]);
          $applications = $apps->fetchAll();

          if(empty($applications)): ?>
            <div class="card-empty">
              <span class="card-empty-ico">📋</span><br>
              You haven't applied to any internships yet.<br><br>
              <a href="/internhub/listings.php" class="btn-gold">Start Applying Now →</a>
            </div>
          <?php else: ?>
            <table class="co-table">
              <thead>
                <tr><th>Internship</th><th>Company</th><th>Applied</th><th>Status</th></tr>
              </thead>
              <tbody>
                <?php foreach($applications as $app): 
                  $appDate = new DateTime($app['created_at']);
                  $statusClass = $app['status'] == 'placed' ? 'b-placed' : 'b-' . $app['status'];
                  $statusText = $app['status'] == 'placed' ? 'Placed ✓' : ucfirst(str_replace('_', ' ', $app['status']));
                ?>
                <tr>
                  <td><?php echo htmlspecialchars($app['title']); ?></td>
                  <td><?php echo htmlspecialchars($app['company']); ?></td>
                  <td><?php echo $appDate->format('d M'); ?></td>
                  <td>
                    <span class="lst-badge <?php echo $statusClass; ?>">
                      <?php echo $statusText; ?>
                    </span>
                  </td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          <?php endif; ?>
        </div>
      </div>

      <!-- MY CONFIRMED PLACEMENTS SECTION - FULLY INTEGRATED -->
      <?php
      $placementsQuery = $pdo->prepare("
          SELECT p.*, i.title, i.company, i.location, i.duration 
          FROM placements p 
          JOIN internships i ON p.internship_id = i.id 
          WHERE p.student_id = :student_id AND p.admin_confirmed = 1
          ORDER BY p.confirmation_date DESC
      ");
      $placementsQuery->execute([':student_id' => $userId]);
      $myConfirmedPlacements = $placementsQuery->fetchAll();
      ?>

      <?php if(!empty($myConfirmedPlacements)): ?>
      <div class="card" style="margin-bottom:1.5rem; border-left: 4px solid #16A34A;">
        <div class="card-head" style="background:#F0FDF4;">
          <div class="card-title">✅ My Confirmed Placements</div>
        </div>
        <div class="card-body">
          <?php foreach($myConfirmedPlacements as $placement): ?>
          <div class="lst-row" style="border-bottom:1px solid #EEF1EF; padding:0.8rem 0;">
            <div>
              <div class="lst-title" style="font-weight:700;"><?php echo htmlspecialchars($placement['title']); ?></div>
              <div class="lst-meta"><?php echo htmlspecialchars($placement['company']); ?> · <?php echo htmlspecialchars($placement['location']); ?> · <?php echo $placement['duration']; ?> months</div>
              <div class="lst-meta" style="color:#16A34A; margin-top:5px;">
                ✓ Placement confirmed on <?php echo date('d M Y', strtotime($placement['confirmation_date'])); ?>
              </div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endif; ?>

      <!-- Original Student Content -->
      <div class="dash-grid">
        <div>
          <div class="card">
            <div class="card-head">
              <div class="card-title">Latest Internships</div>
              <a href="/internhub/listings.php" class="card-lnk">View all <?php echo $totalOpen; ?> →</a>
            </div>
            <div class="card-body" style="padding:0 1.5rem">
              <?php if(empty($recentListings)): ?>
                <div class="card-empty"><span class="card-empty-ico">💼</span>No listings yet — check back soon.</div>
              <?php else: foreach($recentListings as $l):
                $tags = array_slice(array_filter(array_map('trim',explode(',',$l['tags']))),0,2);
                $status = getInternshipStatus($l['deadline']);
              ?>
              <div class="lst-row">
                <div class="lst-logo" style="background:<?php echo htmlspecialchars($l['logo_color']); ?>"><?php echo htmlspecialchars($l['logo']); ?></div>
                <div class="lst-info">
                  <div class="lst-title"><?php echo htmlspecialchars($l['title']); ?></div>
                  <div class="lst-meta"><?php echo htmlspecialchars($l['company']); ?><?php if(!empty($tags)): ?> · <?php echo htmlspecialchars($tags[0]); ?><?php endif; ?></div>
                </div>
                <span class="lst-badge <?php echo $status['class']; ?>">
                  <?php echo $status['text']; ?>
                </span>
                <a href="/internhub/detail.php?id=<?php echo $l['id']; ?>" class="lst-cta">View →</a>
              </div>
              <?php endforeach; endif; ?>
            </div>
          </div>
        </div>

        <div>
          <!-- Profile -->
          <div class="profile-card">
            <div class="profile-head">
              <div class="profile-av"><?php echo strtoupper(substr($firstName,0,1)); ?></div>
              <div class="profile-name"><?php echo htmlspecialchars($user['full_name']); ?></div>
              <div class="profile-email"><?php echo htmlspecialchars($user['email']); ?></div>
            </div>
            <div class="profile-rows">
              <?php foreach([
                ['Course',      $user['course']      ?? '—'],
                ['Student ID',  $user['student_id']  ?? '—'],
                ['Year',        $user['study_year']  ?? '—'],
                ['Readiness',   $enrollment?'✅ Certified':'⬜ Not enrolled'],
              ] as [$k,$v]): ?>
              <div class="profile-row"><span class="pr-key"><?php echo $k; ?></span><span class="pr-val"><?php echo htmlspecialchars($v); ?></span></div>
              <?php endforeach; ?>
            </div>
          </div>

          <!-- Quick Actions -->
          <div class="qa-grid">
            <a href="/internhub/listings.php" class="qa-btn"><span class="qa-ico">💼</span><span class="qa-lbl">Browse</span></a>
            <a href="/internhub/readiness-enroll.php" class="qa-btn"><span class="qa-ico">🎓</span><span class="qa-lbl">Enroll Now</span></a>
            <a href="/internhub/partners.php" class="qa-btn"><span class="qa-ico">🏢</span><span class="qa-lbl">Companies</span></a>
            <a href="/internhub/contact.php" class="qa-btn"><span class="qa-ico">📬</span><span class="qa-lbl">Contact</span></a>
          </div>

          <!-- Readiness -->
          <div class="rc-card">
            <div class="rc-ico">🎓</div>
            <div class="rc-title"><?php echo $enrollment?'Readiness — Enrolled ✅':'Stand out with Readiness'; ?></div>
            <?php if($enrollment): ?>
              <div class="rc-enrolled">
                <span style="font-size:1.1rem">✅</span>
                <div><div class="rc-enrolled-txt">You are certified</div><div class="rc-enrolled-sub">Visible to Featured listing companies</div></div>
              </div>
              <a href="/internhub/readiness.php" class="btn-gold" style="font-size:.82rem;padding:9px 16px">View Program →</a>
            <?php else: ?>
              <div class="rc-body">Companies shortlist Readiness-certified applicants first. Enrol before you apply.</div>
              <div class="rc-tiers">
                <div class="rc-tier"><div class="rc-tier-l"><span>🆓</span><div><div class="rc-tier-name">Free Preview</div><div class="rc-tier-desc">CV template + application guide</div></div></div><div class="rc-price free">FREE</div></div>
                <div class="rc-tier"><div class="rc-tier-l"><span>🥉</span><div><div class="rc-tier-name">Foundation</div><div class="rc-tier-desc">CV, interview prep, communication</div></div></div><div class="rc-price">UGX 30,000</div></div>
                <div class="rc-tier"><div class="rc-tier-l"><span>🥇</span><div><div class="rc-tier-name">Full Certificate</div><div class="rc-tier-desc">All modules + certificate badge</div></div></div><div class="rc-price">UGX 50,000</div></div>
              </div>
              <div style="display:flex;gap:.6rem;flex-wrap:wrap">
                <a href="/internhub/readiness-enroll.php" class="btn-gold" style="font-size:.82rem;padding:9px 14px;background:var(--green);box-shadow:none">Enroll Now →</a>
              </div>
            <?php endif; ?>
          </div>
        </div>
      </div>

    <?php else: ?>
      <!-- COMPANY DASHBOARD -->
      <div class="wb">
        <div>
          <div class="wb-ey">🏢 Company Dashboard</div>
          <h1 class="wb-title"><?php echo $myTotal===0?'Welcome. Post your first listing.':'Manage your listings.'; ?></h1>
          <p class="wb-body">
            <?php if($myTotal===0): ?>
              Your first listing is <strong style="color:#fff">completely free</strong>. Post now, go live within 24 hours.
            <?php else: ?>
              <strong style="color:#fff"><?php echo $myApproved; ?> listing<?php echo $myApproved!==1?'s':''; ?> live</strong> reaching UICT students.
              <?php if($myPending): ?> <strong style="color:var(--gold)"><?php echo $myPending; ?> pending review.</strong><?php endif; ?>
            <?php endif; ?>
          </p>
        </div>
        <div class="wb-actions">
          <a href="/internhub/post.php" class="btn-gold"><?php echo !$hasUsedFreeTrial?'✨ Post Free Listing':'Post New Listing'; ?> →</a>
          <a href="/internhub/listings.php" class="btn-wl">View Live Site</a>
        </div>
      </div>

      <?php if(!$hasUsedFreeTrial): ?>
      <div class="notice n-info">
        <span>🎁</span>
        <div><strong>Your first listing is free.</strong> No payment needed. <a href="/internhub/post.php" style="color:var(--teal-700);font-weight:700">Post it now →</a></div>
      </div>
      <?php endif; ?>

      <div class="stats stats-4">
        <div class="stat-card"><div class="stat-ico">📋</div><div class="stat-num" style="color:var(--ink)"><?php echo $myTotal; ?></div><div class="stat-lbl">Total posted</div></div>
        <div class="stat-card"><div class="stat-ico">⏳</div><div class="stat-num" style="color:var(--amber)"><?php echo $myPending; ?></div><div class="stat-lbl">Pending review</div></div>
        <div class="stat-card"><div class="stat-ico">✅</div><div class="stat-num" style="color:var(--green)"><?php echo $myApproved; ?></div><div class="stat-lbl">Live & approved</div></div>
        <div class="stat-card"><div class="stat-ico">❌</div><div class="stat-num" style="color:var(--red)"><?php echo $myRejected; ?></div><div class="stat-lbl">Rejected</div></div>
      </div>

      <div class="dash-grid">
        <div>
          <div class="card">
            <div class="card-head">
              <div class="card-title">My Listings</div>
              <a href="/internhub/post.php" class="card-lnk">+ Post New</a>
            </div>
            <?php if(empty($myListings)): ?>
              <div class="card-empty">
                <span class="card-empty-ico">📋</span><br>No listings yet. Your first one is free.<br><br>
                <a href="/internhub/post.php" class="btn-gold" style="font-size:.83rem;padding:9px 18px">Post Free Listing →</a>
              </div>
            <?php else: ?>
              <table class="co-table">
                <thead><tr><th>Internship</th><th>Deadline</th><th>Status</th><th>View</th></tr></thead>
                <tbody>
                  <?php foreach($myListings as $l):
                    $dl = new DateTime($l['deadline']);
                    $daysLeft = (int)(new DateTime())->diff($dl)->format('%r%a');
                  ?>
                  <tr>
                    <td>
                      <div class="co-ttitle"><?php echo htmlspecialchars($l['title']); ?></div>
                      <div class="co-tmeta"><?php echo htmlspecialchars($l['field'] ?? '—'); ?> · <?php echo htmlspecialchars($l['duration']); ?> months</div>
                    </td>
                    <td style="font-size:.82rem;color:<?php echo $daysLeft<=7&&$daysLeft>=0?'var(--amber)':($daysLeft<0?'var(--red)':'var(--grey-600)'); ?>">
                      <?php echo $dl->format('d M Y'); ?>
                    </td>
                    <td><span class="lst-badge b-<?php echo $l['status']; ?>"><?php echo ucfirst($l['status']); ?></span></td>
                    <td><a href="/internhub/detail.php?id=<?php echo $l['id']; ?>" class="lst-cta" target="_blank">View →</a></td>
                  </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            <?php endif; ?>
          </div>
        </div>

        <div>
          <div class="post-cta">
            <?php if(!$hasUsedFreeTrial): ?><div class="free-badge">🎁 First listing FREE</div><?php endif; ?>
            <div class="pc-ey">Post an Internship</div>
            <div class="pc-title"><?php echo !$hasUsedFreeTrial?'Start with a free listing.':'Reach more students.'; ?></div>
            <div class="pc-body"><?php echo !$hasUsedFreeTrial?'No payment. We review and publish within 24 hours.':'Standard UGX 150k · Featured UGX 300k.'; ?></div>
            <a href="/internhub/post.php" class="btn-gold" style="font-size:.83rem;padding:9px 16px;display:inline-flex"><?php echo !$hasUsedFreeTrial?'Post Free →':'Post New →'; ?></a>
          </div>

          <div class="profile-card">
            <div class="profile-head">
              <div class="profile-av" style="background:var(--gold-dk);color:#fff">
                <?php
                $words = explode(' ', $user['company_name'] ?? $user['full_name']);
                echo strtoupper(substr($words[0],0,1).(isset($words[1])?substr($words[1],0,1):substr($words[0],1,1)));
                ?>
              </div>
              <div class="profile-name"><?php echo htmlspecialchars($user['company_name'] ?? $user['full_name']); ?></div>
              <div class="profile-email"><?php echo htmlspecialchars($user['email']); ?></div>
            </div>
            <div class="profile-rows">
              <?php foreach([
                ['Industry', $user['industry'] ?? '—'],
                ['Phone',    $user['phone']    ?? '—'],
                ['Location', $user['address']  ?? 'Kampala'],
                ['Listings', $myTotal.' posted'],
              ] as [$k,$v]): ?>
              <div class="profile-row"><span class="pr-key"><?php echo $k; ?></span><span class="pr-val"><?php echo htmlspecialchars($v); ?></span></div>
              <?php endforeach; ?>
            </div>
          </div>

          <div class="pay-guide">
            <div class="pay-guide-head"><div class="pay-guide-title">💳 How to Pay</div></div>
            <div class="pay-guide-body">
              <p>After submitting you get a reference code. Send payment to:</p>
              <div class="momo-row"><span style="background:#FFCC00;color:#111;font-weight:900;font-size:.63rem;padding:2px 6px;border-radius:4px">MTN</span><span>Dial <strong>*165#</strong> → <strong>0771 000 000</strong></span></div>
              <div class="momo-row"><span style="background:#E4002B;color:#fff;font-weight:900;font-size:.63rem;padding:2px 6px;border-radius:4px">AIR</span><span>Dial <strong>*185#</strong> → <strong>0751 000 000</strong></span></div>
            </div>
          </div>
        </div>
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