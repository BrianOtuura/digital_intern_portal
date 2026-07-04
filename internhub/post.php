<?php
$activePage = 'post';
$pageTitle  = 'Post an Internship';
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once 'includes/db.php';
require_once 'includes/header.php';

/* ── Auth: must be logged in as company ── */
$isLoggedIn = isset($_SESSION['user_id']);
$isCompany  = isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'company';

/* ── Step logic ── */
$step = (int)($_GET['step'] ?? 1);
if (!$isLoggedIn) { $step = 0; } // force login nudge
$submitted = $_GET['submitted'] ?? '';
$error     = $_GET['error']     ?? '';

/* ── Selected tier from GET ── */
$tier = $_GET['tier'] ?? 'standard';
$tierData = [
  'standard' => ['name'=>'Standard Listing','price'=>150000,'days'=>60,'label'=>'UGX 150,000'],
  'featured' => ['name'=>'Featured Listing','price'=>300000,'days'=>90,'label'=>'UGX 300,000'],
];
$selected = $tierData[$tier] ?? $tierData['standard'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Post an Internship — Digital Internship Portal</title>
<link rel="preconnect" href="https://fonts.googleapis.com"/>
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,300;0,9..144,600;0,9..144,700;1,9..144,300&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{
  --teal-900:#062E22;--teal-800:#0B4D3B;--teal-700:#0E6B52;
  --teal-600:#128A69;--teal-400:#1DB885;--teal-200:#8EDFC5;
  --teal-100:#C5F0E3;--teal-50:#EDF9F5;
  --gold:#D4A017;--gold-lt:#FBF3D9;--gold-dk:#A67C00;
  --white:#FFFFFF;--off:#F8FAF9;
  --grey-100:#EEF1EF;--grey-200:#D8DFDB;
  --grey-400:#8A9E96;--grey-600:#4A5E56;--ink:#0D1F19;
  --mtn:#FFCC00;--mtn-dark:#1a1a1a;
  --airtel:#E4002B;--airtel-light:#FFF0F2;
  --shadow-sm:0 1px 4px rgba(6,46,34,.07);
  --shadow-md:0 4px 20px rgba(6,46,34,.10);
  --shadow-lg:0 12px 48px rgba(6,46,34,.13);
  --shadow-xl:0 24px 80px rgba(6,46,34,.16);
  --r-sm:6px;--r-md:12px;--r-lg:20px;--r-xl:32px;
  --ease:cubic-bezier(.4,0,.2,1);--t:.2s;
}
html{scroll-behavior:smooth}
body{font-family:'Plus Jakarta Sans',sans-serif;background:var(--off);color:var(--ink);overflow-x:hidden;-webkit-font-smoothing:antialiased}
a{text-decoration:none;color:inherit}
::-webkit-scrollbar{width:5px}
::-webkit-scrollbar-track{background:var(--off)}
::-webkit-scrollbar-thumb{background:var(--grey-200);border-radius:99px}

/* NAV */
.nav{position:sticky;top:0;z-index:200;height:68px;display:flex;align-items:center;justify-content:space-between;padding:0 2.5rem;background:rgba(255,255,255,.95);backdrop-filter:blur(14px);border-bottom:1px solid var(--grey-100);box-shadow:var(--shadow-sm)}
.nav-brand{display:flex;align-items:center;gap:10px;font-family:'Fraunces',serif;font-size:1.1rem;font-weight:700;color:var(--teal-800);letter-spacing:-.01em}
.nav-brand-dot{width:8px;height:8px;border-radius:50%;background:var(--teal-600);box-shadow:0 0 0 3px var(--teal-100)}
.nav-links{display:flex;align-items:center;gap:4px}
.nav-links a{font-size:.875rem;font-weight:500;color:var(--grey-600);padding:6px 13px;border-radius:var(--r-sm);transition:color var(--t) var(--ease),background var(--t) var(--ease)}
.nav-links a:hover{color:var(--ink);background:var(--off)}
.nav-links a.active{color:var(--teal-700);font-weight:600}
.nav-cta{background:var(--teal-800)!important;color:#fff!important;font-weight:600!important;border-radius:var(--r-sm)!important;padding:8px 18px!important;box-shadow:0 2px 8px rgba(11,77,59,.28)!important;transition:all var(--t) var(--ease)!important}
.nav-cta:hover{background:var(--teal-900)!important;transform:translateY(-1px)!important}

/* PAGE HERO */
.page-hero{background:var(--teal-900);position:relative;overflow:hidden;padding:4rem 0 3.5rem}
.page-hero::before{content:'';position:absolute;inset:0;background:radial-gradient(ellipse 70% 60% at 65% 40%,rgba(18,138,105,.3) 0%,transparent 65%);pointer-events:none}
.page-hero::after{content:'';position:absolute;inset:0;background-image:linear-gradient(rgba(255,255,255,.025) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.025) 1px,transparent 1px);background-size:52px 52px;pointer-events:none}
.hero-inner{position:relative;z-index:1;max-width:1200px;margin:0 auto;padding:0 2.5rem}
.hero-badge{display:inline-flex;align-items:center;gap:8px;font-size:.7rem;font-weight:600;letter-spacing:.14em;text-transform:uppercase;color:var(--teal-400);background:rgba(29,184,133,.12);border:1px solid rgba(29,184,133,.25);padding:5px 14px;border-radius:99px;margin-bottom:1.2rem}
.hero-title{font-family:'Fraunces',serif;font-size:clamp(2rem,4vw,2.8rem);font-weight:700;color:#fff;line-height:1.1;letter-spacing:-.025em;margin-bottom:.6rem}
.hero-sub{font-size:.95rem;color:rgba(255,255,255,.6);line-height:1.7;max-width:540px}

/* STEPPER */
.stepper{display:flex;align-items:center;gap:0;max-width:1200px;margin:0 auto;padding:1.5rem 2.5rem 0;position:relative;z-index:1}
.step-item{display:flex;align-items:center;gap:.6rem;flex:1}
.step-item:last-child{flex:0}
.step-circle{width:32px;height:32px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-family:'Fraunces',serif;font-weight:700;font-size:.85rem;flex-shrink:0;transition:all var(--t) var(--ease)}
.step-circle.done{background:var(--teal-600);color:#fff}
.step-circle.active{background:#fff;color:var(--teal-800);box-shadow:0 0 0 4px rgba(255,255,255,.2)}
.step-circle.waiting{background:rgba(255,255,255,.1);color:rgba(255,255,255,.35);border:1px solid rgba(255,255,255,.15)}
.step-label{font-size:.75rem;font-weight:500;color:rgba(255,255,255,.55);white-space:nowrap}
.step-label.active{color:#fff;font-weight:600}
.step-label.done{color:var(--teal-400)}
.step-line{flex:1;height:1px;background:rgba(255,255,255,.12);margin:0 .5rem}
.step-line.done{background:var(--teal-600)}

/* LAYOUT */
.post-layout{max-width:1200px;margin:0 auto;padding:2.5rem 2.5rem 6rem;display:grid;grid-template-columns:1fr 340px;gap:2rem;align-items:start}

/* SHARED CARD */
.card{background:var(--white);border:1px solid var(--grey-100);border-radius:var(--r-xl);overflow:hidden}
.card-header{padding:1.8rem 2.2rem 1.4rem;border-bottom:1px solid var(--grey-100)}
.card-title{font-family:'Fraunces',serif;font-weight:700;font-size:1.15rem;color:var(--ink);margin-bottom:.25rem}
.card-sub{font-size:.85rem;color:var(--grey-600)}
.card-body{padding:2rem 2.2rem}

/* FORM */
.field-row{display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1.1rem}
.field{display:flex;flex-direction:column;gap:.4rem;margin-bottom:1.1rem}
.field:last-child{margin-bottom:0}
.field-label{font-size:.775rem;font-weight:600;color:var(--ink)}
.field-label span{color:var(--teal-600)}
.field-input,.field-select,.field-textarea{width:100%;font-family:'Plus Jakarta Sans',sans-serif;font-size:.9rem;color:var(--ink);background:var(--off);border:1.5px solid var(--grey-200);border-radius:var(--r-md);padding:11px 14px;outline:none;transition:border-color var(--t) var(--ease),box-shadow var(--t) var(--ease)}
.field-input:focus,.field-select:focus,.field-textarea:focus{border-color:var(--teal-600);box-shadow:0 0 0 3px var(--teal-50);background:var(--white)}
.field-input::placeholder,.field-textarea::placeholder{color:var(--grey-400)}
.field-textarea{resize:vertical;min-height:110px;line-height:1.65}
.field-select{cursor:pointer;appearance:none;background-image:url("data:image/svg+xml,%3Csvg width='12' height='8' viewBox='0 0 12 8' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1 1L6 7L11 1' stroke='%238A9E96' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 14px center;padding-right:36px}
.field-hint{font-size:.72rem;color:var(--grey-400);margin-top:3px;line-height:1.5}
.section-div{display:flex;align-items:center;gap:.75rem;margin:1.4rem 0 1.2rem}
.section-div-line{flex:1;height:1px;background:var(--grey-100)}
.section-div-text{font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--grey-400);white-space:nowrap}

.btn-submit{width:100%;padding:13px;background:var(--teal-800);color:#fff;font-family:'Plus Jakarta Sans',sans-serif;font-weight:700;font-size:.95rem;border:none;border-radius:var(--r-md);cursor:pointer;box-shadow:0 2px 10px rgba(11,77,59,.25);transition:all var(--t) var(--ease);display:flex;align-items:center;justify-content:center;gap:8px;margin-top:1.5rem}
.btn-submit:hover{background:var(--teal-900);transform:translateY(-1px)}

.alert{border-radius:var(--r-md);padding:.9rem 1.1rem;margin-bottom:1.4rem;font-size:.86rem;display:flex;align-items:flex-start;gap:.6rem}
.alert-error{background:#FFF1F1;border:1px solid #FECACA;color:#B91C1C}
.alert-info{background:var(--teal-50);border:1px solid var(--teal-100);color:var(--teal-700)}

/* TIER SELECTOR */
.tier-selector{display:grid;grid-template-columns:1fr 1fr;gap:.85rem;margin-bottom:1.5rem}
.tier-option{border:1.5px solid var(--grey-200);border-radius:var(--r-lg);padding:1.3rem;cursor:pointer;transition:all var(--t) var(--ease);position:relative}
.tier-option:hover{border-color:var(--teal-400);background:var(--teal-50)}
.tier-option.selected{border-color:var(--teal-600);background:var(--teal-50);box-shadow:0 0 0 3px var(--teal-50)}
.tier-radio{display:none}
.tier-name{font-family:'Fraunces',serif;font-weight:700;font-size:.95rem;color:var(--ink);margin-bottom:.25rem}
.tier-price{font-family:'Fraunces',serif;font-size:1.4rem;font-weight:700;color:var(--teal-700);margin-bottom:.3rem}
.tier-desc{font-size:.75rem;color:var(--grey-600);line-height:1.5}
.tier-check{position:absolute;top:.9rem;right:.9rem;width:20px;height:20px;border-radius:50%;border:1.5px solid var(--grey-200);background:var(--white);display:flex;align-items:center;justify-content:center;transition:all var(--t) var(--ease)}
.tier-option.selected .tier-check{background:var(--teal-600);border-color:var(--teal-600);color:#fff;font-size:.7rem}
.tier-badge{display:inline-block;font-size:.65rem;font-weight:700;padding:2px 8px;border-radius:99px;background:var(--gold);color:var(--ink);margin-bottom:.4rem}

/* PAYMENT SECTION */
.payment-section{margin-top:2rem}
.payment-title{font-family:'Fraunces',serif;font-weight:700;font-size:1rem;color:var(--ink);margin-bottom:1rem}
.momo-cards{display:flex;flex-direction:column;gap:.85rem}
.momo-card{border-radius:var(--r-lg);overflow:hidden;border:1.5px solid var(--grey-200)}
.momo-card-head{display:flex;align-items:center;justify-content:space-between;padding:1rem 1.3rem;cursor:pointer;transition:all var(--t) var(--ease)}
.momo-card-head:hover{background:var(--off)}
.momo-brand{display:flex;align-items:center;gap:.9rem}
.momo-logo{width:40px;height:40px;border-radius:var(--r-sm);display:flex;align-items:center;justify-content:center;font-weight:900;font-size:.78rem;letter-spacing:-.02em;flex-shrink:0}
.momo-logo-mtn{background:var(--mtn);color:var(--mtn-dark)}
.momo-logo-airtel{background:var(--airtel);color:#fff}
.momo-name{font-family:'Fraunces',serif;font-weight:700;font-size:.95rem;color:var(--ink)}
.momo-sub{font-size:.75rem;color:var(--grey-600);margin-top:1px}
.momo-toggle{font-size:.85rem;color:var(--grey-400);transition:transform var(--t) var(--ease)}
.momo-toggle.open{transform:rotate(180deg)}
.momo-body{border-top:1px solid var(--grey-100);padding:1.3rem;background:var(--off);display:none}
.momo-body.open{display:block}
.momo-steps{display:flex;flex-direction:column;gap:.7rem;margin-bottom:1rem}
.momo-step{display:flex;gap:.85rem;align-items:flex-start}
.momo-step-num{width:24px;height:24px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:.72rem;font-weight:700;flex-shrink:0;margin-top:1px}
.momo-step-num-mtn{background:var(--mtn);color:var(--mtn-dark)}
.momo-step-num-airtel{background:var(--airtel);color:#fff}
.momo-step-text{font-size:.85rem;color:var(--grey-600);line-height:1.55}
.momo-step-text strong{color:var(--ink);font-weight:600}
.momo-ref-box{background:var(--white);border:1.5px dashed var(--grey-200);border-radius:var(--r-md);padding:1rem 1.2rem;margin-top:.5rem}
.momo-ref-label{font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--grey-400);margin-bottom:.3rem}
.momo-ref-value{font-family:'Fraunces',serif;font-weight:700;font-size:1.05rem;color:var(--ink)}
.momo-ref-note{font-size:.75rem;color:var(--grey-400);margin-top:.3rem}
.momo-amount-pill{display:inline-flex;align-items:center;gap:6px;background:var(--gold-lt);border:1px solid rgba(212,160,23,.2);border-radius:99px;padding:4px 12px;font-size:.78rem;font-weight:700;color:var(--gold-dk);margin-bottom:.8rem}

/* CONFIRMATION UPLOAD */
.confirm-upload{background:var(--teal-50);border:1.5px dashed var(--teal-200);border-radius:var(--r-lg);padding:1.3rem;text-align:center;margin-top:1rem;cursor:pointer;transition:all var(--t) var(--ease)}
.confirm-upload:hover{background:var(--teal-100);border-color:var(--teal-400)}
.confirm-upload-icon{font-size:1.5rem;margin-bottom:.4rem}
.confirm-upload-text{font-size:.83rem;font-weight:600;color:var(--teal-700)}
.confirm-upload-sub{font-size:.73rem;color:var(--grey-600);margin-top:2px}

/* SIDEBAR */
.post-sidebar{display:flex;flex-direction:column;gap:1.25rem;position:sticky;top:88px}

/* ORDER SUMMARY */
.summary-card{background:var(--teal-800);border-radius:var(--r-xl);overflow:hidden;position:relative}
.summary-card::before{content:'';position:absolute;top:-20%;right:-10%;width:200px;height:200px;border-radius:50%;background:radial-gradient(circle,rgba(29,184,133,.2),transparent 70%);pointer-events:none}
.summary-inner{padding:1.8rem;position:relative;z-index:1}
.summary-eyebrow{font-size:.68rem;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:var(--teal-400);margin-bottom:.5rem}
.summary-title{font-family:'Fraunces',serif;font-weight:700;font-size:1.1rem;color:#fff;margin-bottom:1.2rem}
.summary-rows{display:flex;flex-direction:column;gap:.55rem;margin-bottom:1.3rem}
.summary-row{display:flex;justify-content:space-between;align-items:center;font-size:.84rem}
.summary-row-key{color:rgba(255,255,255,.55)}
.summary-row-val{color:#fff;font-weight:600}
.summary-divider{height:1px;background:rgba(255,255,255,.1);margin-bottom:1.2rem}
.summary-total{display:flex;justify-content:space-between;align-items:center;margin-bottom:1.3rem}
.summary-total-key{font-size:.85rem;color:rgba(255,255,255,.7)}
.summary-total-val{font-family:'Fraunces',serif;font-size:1.5rem;font-weight:700;color:var(--gold)}
.summary-note{font-size:.75rem;color:rgba(255,255,255,.4);line-height:1.55;text-align:center;margin-top:.8rem}

/* PROCESS CARD */
.process-card{background:var(--white);border:1px solid var(--grey-100);border-radius:var(--r-lg)}
.process-title{font-family:'Fraunces',serif;font-weight:700;font-size:.95rem;color:var(--ink);padding:1.1rem 1.3rem;border-bottom:1px solid var(--grey-100)}
.process-steps{display:flex;flex-direction:column}
.process-step{display:flex;gap:.9rem;padding:.9rem 1.3rem;border-bottom:1px solid var(--grey-100);align-items:flex-start}
.process-step:last-child{border-bottom:none}
.process-step-num{width:22px;height:22px;border-radius:50%;background:var(--teal-50);border:1px solid var(--teal-100);display:flex;align-items:center;justify-content:center;font-size:.7rem;font-weight:700;color:var(--teal-700);flex-shrink:0;margin-top:1px}
.process-step-title{font-size:.83rem;font-weight:600;color:var(--ink);margin-bottom:2px}
.process-step-body{font-size:.77rem;color:var(--grey-600);line-height:1.5}

/* SUCCESS STATE */
.success-wrap{max-width:640px;margin:4rem auto;padding:0 2.5rem}
.success-card{background:var(--white);border:1px solid var(--grey-100);border-radius:var(--r-xl);padding:3rem;text-align:center}
.success-icon{font-size:3.5rem;margin-bottom:1.2rem}
.success-title{font-family:'Fraunces',serif;font-size:1.8rem;font-weight:700;color:var(--ink);margin-bottom:.6rem}
.success-body{font-size:.93rem;color:var(--grey-600);line-height:1.75;margin-bottom:2rem}
.success-ref{background:var(--off);border:1px solid var(--grey-200);border-radius:var(--r-lg);padding:1.2rem;margin-bottom:2rem;display:inline-block;min-width:260px}
.success-ref-label{font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--grey-400);margin-bottom:.4rem}
.success-ref-val{font-family:'Fraunces',serif;font-size:1.3rem;font-weight:700;color:var(--teal-700)}
.success-actions{display:flex;gap:.75rem;justify-content:center;flex-wrap:wrap}
.btn-primary{display:inline-flex;align-items:center;gap:7px;background:var(--teal-800);color:#fff;font-family:'Plus Jakarta Sans',sans-serif;font-weight:600;font-size:.9rem;padding:12px 24px;border-radius:var(--r-sm);border:none;cursor:pointer;box-shadow:0 2px 8px rgba(11,77,59,.25);transition:all var(--t) var(--ease)}
.btn-primary:hover{background:var(--teal-900)}
.btn-outline{display:inline-flex;align-items:center;gap:7px;background:transparent;color:var(--teal-800);font-family:'Plus Jakarta Sans',sans-serif;font-weight:600;font-size:.9rem;padding:12px 24px;border-radius:var(--r-sm);border:1.5px solid var(--teal-800);transition:all var(--t) var(--ease)}
.btn-outline:hover{background:var(--teal-50)}

/* LOGIN NUDGE */
.login-nudge{max-width:560px;margin:4rem auto;padding:0 2.5rem;text-align:center}
.login-nudge-card{background:var(--white);border:1px solid var(--grey-100);border-radius:var(--r-xl);padding:3rem}
.login-nudge-icon{font-size:2.5rem;margin-bottom:1rem}
.login-nudge-title{font-family:'Fraunces',serif;font-size:1.5rem;font-weight:700;color:var(--ink);margin-bottom:.6rem}
.login-nudge-body{font-size:.9rem;color:var(--grey-600);line-height:1.72;margin-bottom:1.8rem}

/* FOOTER */
.footer{background:#040F0A;padding:5rem 0 2.5rem}
.footer-inner{max-width:1200px;margin:0 auto;padding:0 2.5rem}
.footer-top{display:grid;grid-template-columns:2.5fr 1fr 1fr 1.2fr;gap:3rem;padding-bottom:3rem;border-bottom:1px solid rgba(255,255,255,.07);margin-bottom:2rem}
.footer-brand-name{font-family:'Fraunces',serif;font-size:1.1rem;font-weight:700;color:#fff;margin-bottom:.8rem}
.footer-brand-desc{font-size:.85rem;color:rgba(255,255,255,.4);line-height:1.7;margin-bottom:1.2rem;max-width:260px}
.footer-badge{display:inline-flex;align-items:center;gap:7px;background:rgba(29,184,133,.1);border:1px solid rgba(29,184,133,.2);border-radius:99px;padding:4px 12px;font-size:.72rem;font-weight:600;color:var(--teal-400)}
.footer-badge-dot{width:5px;height:5px;border-radius:50%;background:var(--teal-400)}
.footer-col-title{font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.12em;color:rgba(255,255,255,.3);margin-bottom:1.2rem}
.footer-col a,.footer-col span{display:block;font-size:.86rem;color:rgba(255,255,255,.5);text-decoration:none;margin-bottom:.55rem;transition:color var(--t) var(--ease)}
.footer-col a:hover{color:rgba(255,255,255,.9)}
.footer-bottom{display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem;font-size:.78rem;color:rgba(255,255,255,.22)}

/* RESPONSIVE */
@media(max-width:1024px){
  .post-layout{grid-template-columns:1fr;gap:2rem}
  .post-sidebar{position:static}
  .footer-top{grid-template-columns:1fr 1fr;gap:2rem}
}
@media(max-width:768px){
  .nav{padding:0 1.25rem}
  .nav-links a:not(.nav-cta){display:none}
  .hero-inner{padding:0 1.25rem}
  .stepper{padding:1.25rem 1.25rem 0}
  .step-label{display:none}
  .post-layout{padding:1.5rem 1.25rem 4rem}
  .field-row{grid-template-columns:1fr}
  .tier-selector{grid-template-columns:1fr}
  .footer-inner{padding:0 1.25rem}
  .footer-top{grid-template-columns:1fr}
}
</style>
</head>
<body>

<!-- NAV -->
<nav class="nav">
  <a class="nav-brand" href="/internhub/index.php">
    <span class="nav-brand-dot"></span>
    Digital Internship Portal
  </a>
  <div class="nav-links">
    <a href="/internhub/index.php">Home</a>
    <a href="/internhub/listings.php">Browse Listings</a>
    <a href="/internhub/readiness.php">Readiness Program</a>
    <a href="/internhub/partners.php">For Companies</a>
    <a href="/internhub/about.php">About</a>
    <?php if($isLoggedIn): ?>
      <a href="/internhub/auth/dashboard.php"><?php echo htmlspecialchars(explode(' ',$_SESSION['user_name'])[0]); ?> ▾</a>
      <a href="/internhub/auth/actions/logout.php" class="nav-cta">Log Out</a>
    <?php else: ?>
      <a href="/internhub/auth/login.php">Sign In</a>
      <a href="/internhub/auth/register.php?type=company" class="nav-cta">Register Company</a>
    <?php endif; ?>
  </div>
</nav>

<?php if($submitted === '1'): ?>
<!-- ══════════════ SUCCESS STATE ══════════════ -->
<?php
  $refCode = strtoupper('DIP-'.date('ymd').'-'.substr(md5(uniqid()),0,6));
?>
<div class="success-wrap">
  <div class="success-card">
    <div class="success-icon">🎉</div>
    <div class="success-title">Listing submitted!</div>
    <p class="success-body">
      Your internship listing has been received and is <strong>pending admin review</strong>.
      We aim to review all listings within <strong>24 hours</strong>.<br/><br/>
      Complete your Mobile Money payment using the reference below to activate your listing.
      Once payment is confirmed and the listing is approved, it goes live immediately.
    </p>
    <div class="success-ref">
      <div class="success-ref-label">Your Payment Reference</div>
      <div class="success-ref-val"><?php echo $refCode; ?></div>
    </div>
    <div style="background:var(--off);border:1px solid var(--grey-200);border-radius:var(--r-lg);padding:1.4rem;margin-bottom:2rem;text-align:left">
      <div style="font-family:'Fraunces',serif;font-weight:700;font-size:.95rem;color:var(--ink);margin-bottom:.9rem">Complete Your Payment</div>
      <div style="display:flex;flex-direction:column;gap:.65rem">
        <div style="background:var(--white);border:1px solid var(--grey-100);border-radius:var(--r-md);padding:.9rem 1.1rem">
          <div style="display:flex;align-items:center;gap:.8rem;margin-bottom:.5rem">
            <span style="background:#FFCC00;color:#1a1a1a;font-weight:900;font-size:.72rem;padding:3px 8px;border-radius:4px">MTN</span>
            <span style="font-size:.83rem;font-weight:600;color:var(--ink)">MTN Mobile Money</span>
          </div>
          <div style="font-size:.82rem;color:var(--grey-600)">Dial <strong>*165#</strong> → Send Money → Enter <strong>0771 000 000</strong> → Amount: <strong><?php echo $selected['label']; ?></strong> → Reference: <strong><?php echo $refCode; ?></strong></div>
        </div>
        <div style="background:var(--white);border:1px solid var(--grey-100);border-radius:var(--r-md);padding:.9rem 1.1rem">
          <div style="display:flex;align-items:center;gap:.8rem;margin-bottom:.5rem">
            <span style="background:#E4002B;color:#fff;font-weight:900;font-size:.72rem;padding:3px 8px;border-radius:4px">AIRTEL</span>
            <span style="font-size:.83rem;font-weight:600;color:var(--ink)">Airtel Money</span>
          </div>
          <div style="font-size:.82rem;color:var(--grey-600)">Dial <strong>*185#</strong> → Send Money → Enter <strong>0751 000 000</strong> → Amount: <strong><?php echo $selected['label']; ?></strong> → Reference: <strong><?php echo $refCode; ?></strong></div>
        </div>
      </div>
      <div style="font-size:.77rem;color:var(--grey-400);margin-top:.9rem;line-height:1.55">
        📧 After paying, WhatsApp your payment screenshot to <strong>+256 700 000 000</strong> with your reference code. Admin will confirm within 2 hours during business hours.
      </div>
    </div>
    <div class="success-actions">
      <a href="/internhub/listings.php" class="btn-primary">Browse Listings</a>
      <a href="/internhub/post.php" class="btn-outline">Post Another</a>
    </div>
  </div>
</div>

<?php elseif(!$isLoggedIn): ?>
<!-- ══════════════ LOGIN NUDGE ══════════════ -->
<div class="login-nudge">
  <div class="login-nudge-card">
    <div class="login-nudge-icon">🏢</div>
    <div class="login-nudge-title">Company account required</div>
    <p class="login-nudge-body">You need to be signed in as a company to post an internship listing. Register for free — it takes under 3 minutes.</p>
    <div style="display:flex;gap:.75rem;justify-content:center;flex-wrap:wrap">
      <a href="/internhub/auth/register.php?type=company" class="btn-primary">Register as Company</a>
      <a href="/internhub/auth/login.php?type=company&next=/internhub/post.php" class="btn-outline">Sign In</a>
    </div>
  </div>
</div>

<?php else: ?>
<!-- ══════════════ MAIN FORM ══════════════ -->

<!-- HERO -->
<section class="page-hero">
  <div class="hero-inner">
    <div class="hero-badge">🏢 Post an Internship</div>
    <h1 class="hero-title">Reach UICT's best students.</h1>
    <p class="hero-sub">Fill in your listing details, choose a package, and pay via Mobile Money. Live within 24 hours of admin approval.</p>
  </div>
  <!-- STEPPER -->
  <div class="stepper">
    <div class="step-item">
      <div class="step-circle active">1</div>
      <span class="step-label active">Listing Details</span>
      <div class="step-line"></div>
    </div>
    <div class="step-item">
      <div class="step-circle waiting">2</div>
      <span class="step-label">Choose Package</span>
      <div class="step-line"></div>
    </div>
    <div class="step-item">
      <div class="step-circle waiting">3</div>
      <span class="step-label">Pay via MoMo</span>
      <div class="step-line"></div>
    </div>
    <div class="step-item">
      <div class="step-circle waiting">4</div>
      <span class="step-label">Go Live</span>
    </div>
  </div>
</section>

<div class="post-layout">

  <!-- MAIN FORM -->
  <div>
    <form method="POST" action="/internhub/actions/post_action.php">
      <?php if($error): ?>
        <div class="alert alert-error" style="margin-bottom:1.5rem">⚠️ <?php echo htmlspecialchars($error); ?></div>
      <?php endif; ?>

      <!-- LISTING DETAILS -->
      <div class="card" style="margin-bottom:1.5rem">
        <div class="card-header">
          <div class="card-title">Internship Details</div>
          <div class="card-sub">Tell students about the role. Be specific — better descriptions get more applications.</div>
        </div>
        <div class="card-body">
          <div class="field-row">
            <div class="field">
              <label class="field-label">Job Title <span>*</span></label>
              <input class="field-input" type="text" name="title" placeholder="e.g. Web Development Intern" required/>
            </div>
            <div class="field">
              <label class="field-label">Company Name <span>*</span></label>
              <input class="field-input" type="text" name="company"
                value="<?php echo htmlspecialchars($_SESSION['company_name'] ?? ''); ?>" required/>
            </div>
          </div>
          <div class="field-row">
            <div class="field">
              <label class="field-label">Location <span>*</span></label>
              <input class="field-input" type="text" name="location" placeholder="e.g. Kampala CBD" required/>
            </div>
            <div class="field">
              <label class="field-label">Field / Department <span>*</span></label>
              <select class="field-select" name="field" required>
                <option value="">Select field</option>
                <option>Web Development</option>
                <option>Mobile Development</option>
                <option>Data Science / Analytics</option>
                <option>Cybersecurity</option>
                <option>Networking / Infrastructure</option>
                <option>UI/UX Design</option>
                <option>Database Administration</option>
                <option>Software Engineering</option>
                <option>IT Support</option>
                <option>Other</option>
              </select>
            </div>
          </div>
          <div class="field">
            <label class="field-label">Role Description <span>*</span></label>
            <textarea class="field-textarea" name="description" placeholder="Describe the internship role, what the intern will work on, the team they'll join, and what makes this opportunity valuable..." required></textarea>
          </div>

          <div class="section-div">
            <div class="section-div-line"></div>
            <span class="section-div-text">Responsibilities & Requirements</span>
            <div class="section-div-line"></div>
          </div>

          <div class="field">
            <label class="field-label">Key Responsibilities <span>*</span></label>
            <textarea class="field-textarea" name="responsibilities" placeholder="List each responsibility on a new line. e.g.&#10;Build and maintain web interfaces&#10;Assist with database queries&#10;Attend weekly team stand-ups" style="min-height:100px" required></textarea>
            <div class="field-hint">One responsibility per line — we'll format them as a bullet list for students.</div>
          </div>
          <div class="field">
            <label class="field-label">Requirements / Qualifications</label>
            <textarea class="field-textarea" name="requirements" placeholder="e.g.&#10;Currently enrolled at UICT&#10;Basic knowledge of HTML/CSS&#10;Good communication skills" style="min-height:90px"></textarea>
            <div class="field-hint">One requirement per line.</div>
          </div>

          <div class="section-div">
            <div class="section-div-line"></div>
            <span class="section-div-text">Terms & Contact</span>
            <div class="section-div-line"></div>
          </div>

          <div class="field-row">
            <div class="field">
              <label class="field-label">Duration (months) <span>*</span></label>
              <select class="field-select" name="duration" required>
                <option value="1">1 month</option>
                <option value="2">2 months</option>
                <option value="3" selected>3 months</option>
                <option value="4">4 months</option>
                <option value="6">6 months</option>
              </select>
            </div>
            <div class="field">
              <label class="field-label">Number of Positions</label>
              <input class="field-input" type="number" name="slots" min="1" max="20" value="1"/>
            </div>
          </div>
          <div class="field-row">
            <div class="field">
              <label class="field-label">Stipend / Pay Type <span>*</span></label>
              <select class="field-select" name="stipend" required>
                <option value="Unpaid">Unpaid</option>
                <option value="Transport Allowance">Transport Allowance Provided</option>
                <option value="Paid – Negotiable">Paid – Negotiable</option>
                <option value="UGX 100,000/month">UGX 100,000 / month</option>
                <option value="UGX 200,000/month">UGX 200,000 / month</option>
                <option value="UGX 300,000+/month">UGX 300,000+ / month</option>
              </select>
            </div>
            <div class="field">
              <label class="field-label">Application Deadline <span>*</span></label>
              <input class="field-input" type="date" name="deadline"
                min="<?php echo date('Y-m-d', strtotime('+3 days')); ?>"
                value="<?php echo date('Y-m-d', strtotime('+60 days')); ?>" required/>
            </div>
          </div>
          <div class="field">
            <label class="field-label">Application Contact Email <span>*</span></label>
            <input class="field-input" type="email" name="contact"
              placeholder="hr@yourcompany.co.ug"
              value="<?php echo htmlspecialchars($_SESSION['user_email'] ?? ''); ?>" required/>
            <div class="field-hint">Student applications will be sent directly to this address.</div>
          </div>
          <div class="field">
            <label class="field-label">Skill Tags</label>
            <input class="field-input" type="text" name="tags" placeholder="e.g. PHP, MySQL, JavaScript, Networking"/>
            <div class="field-hint">Comma-separated. These help students find your listing when filtering.</div>
          </div>
        </div>
      </div>

      <!-- PACKAGE SELECTOR -->
      <div class="card" style="margin-bottom:1.5rem">
        <div class="card-header">
          <div class="card-title">Choose Your Package</div>
          <div class="card-sub">Both packages include admin review, UICT-only applicants, and direct email applications.</div>
        </div>
        <div class="card-body">
          <div class="tier-selector">
            <label class="tier-option <?php echo $tier==='standard'?'selected':''; ?>" onclick="selectTier('standard', this)">
              <input type="radio" name="tier" value="standard" class="tier-radio" <?php echo $tier==='standard'?'checked':''; ?>/>
              <div class="tier-name">Standard Listing</div>
              <div class="tier-price">UGX 150,000</div>
              <div class="tier-desc">60-day active period · Admin verified · All UICT students</div>
              <div class="tier-check"><?php echo $tier==='standard'?'✓':''; ?></div>
            </label>
            <label class="tier-option <?php echo $tier==='featured'?'selected':''; ?>" onclick="selectTier('featured', this)">
              <input type="radio" name="tier" value="featured" class="tier-radio" <?php echo $tier==='featured'?'checked':''; ?>/>
              <div class="tier-badge">Most Chosen</div>
              <div class="tier-name">Featured Listing</div>
              <div class="tier-price">UGX 300,000</div>
              <div class="tier-desc">90-day active period · Priority placement · Readiness-certified filter · Logo badge</div>
              <div class="tier-check"><?php echo $tier==='featured'?'✓':''; ?></div>
            </label>
          </div>

          <!-- PAYMENT INSTRUCTIONS -->
          <div class="payment-section">
            <div class="payment-title">Pay via Mobile Money</div>
            <p style="font-size:.85rem;color:var(--grey-600);margin-bottom:1rem;line-height:1.65">
              After submitting your listing, you'll receive a unique payment reference. Send the exact amount to either number below and WhatsApp your screenshot to confirm. Your listing goes live once payment is confirmed and admin approves.
            </p>
            <div class="momo-cards">
              <!-- MTN -->
              <div class="momo-card">
                <div class="momo-card-head" onclick="toggleMomo('mtn')">
                  <div class="momo-brand">
                    <div class="momo-logo momo-logo-mtn">MTN</div>
                    <div>
                      <div class="momo-name">MTN Mobile Money</div>
                      <div class="momo-sub">Dial *165# · Send to 0771 000 000</div>
                    </div>
                  </div>
                  <span class="momo-toggle" id="mtn-toggle">▾</span>
                </div>
                <div class="momo-body" id="mtn-body">
                  <div class="momo-amount-pill">💰 Amount: <span id="mtn-amount"><?php echo $selected['label']; ?></span></div>
                  <div class="momo-steps">
                    <div class="momo-step">
                      <div class="momo-step-num momo-step-num-mtn">1</div>
                      <div class="momo-step-text">Dial <strong>*165#</strong> on your MTN line and select <strong>Send Money</strong></div>
                    </div>
                    <div class="momo-step">
                      <div class="momo-step-num momo-step-num-mtn">2</div>
                      <div class="momo-step-text">Enter the number <strong>0771 000 000</strong> (Digital Internship Portal)</div>
                    </div>
                    <div class="momo-step">
                      <div class="momo-step-num momo-step-num-mtn">3</div>
                      <div class="momo-step-text">Enter the exact amount — <strong id="mtn-amount-2"><?php echo $selected['label']; ?></strong></div>
                    </div>
                    <div class="momo-step">
                      <div class="momo-step-num momo-step-num-mtn">4</div>
                      <div class="momo-step-text">In the reference/reason field, enter your <strong>listing reference code</strong> (shown after submission)</div>
                    </div>
                    <div class="momo-step">
                      <div class="momo-step-num momo-step-num-mtn">5</div>
                      <div class="momo-step-text">WhatsApp the payment confirmation screenshot to <strong>+256 700 000 000</strong></div>
                    </div>
                  </div>
                  <div class="momo-ref-box">
                    <div class="momo-ref-label">Send payment to</div>
                    <div class="momo-ref-value">0771 000 000 · DIP Admin</div>
                    <div class="momo-ref-note">Your reference code is generated when you submit the form</div>
                  </div>
                </div>
              </div>
              <!-- AIRTEL -->
              <div class="momo-card">
                <div class="momo-card-head" onclick="toggleMomo('airtel')">
                  <div class="momo-brand">
                    <div class="momo-logo momo-logo-airtel">AIR</div>
                    <div>
                      <div class="momo-name">Airtel Money</div>
                      <div class="momo-sub">Dial *185# · Send to 0751 000 000</div>
                    </div>
                  </div>
                  <span class="momo-toggle" id="airtel-toggle">▾</span>
                </div>
                <div class="momo-body" id="airtel-body">
                  <div class="momo-amount-pill">💰 Amount: <span id="airtel-amount"><?php echo $selected['label']; ?></span></div>
                  <div class="momo-steps">
                    <div class="momo-step">
                      <div class="momo-step-num momo-step-num-airtel">1</div>
                      <div class="momo-step-text">Dial <strong>*185#</strong> on your Airtel line and select <strong>Send Money</strong></div>
                    </div>
                    <div class="momo-step">
                      <div class="momo-step-num momo-step-num-airtel">2</div>
                      <div class="momo-step-text">Enter the number <strong>0751 000 000</strong> (Digital Internship Portal)</div>
                    </div>
                    <div class="momo-step">
                      <div class="momo-step-num momo-step-num-airtel">3</div>
                      <div class="momo-step-text">Enter the exact amount — <strong id="airtel-amount-2"><?php echo $selected['label']; ?></strong></div>
                    </div>
                    <div class="momo-step">
                      <div class="momo-step-num momo-step-num-airtel">4</div>
                      <div class="momo-step-text">In the reference/reason field, enter your <strong>listing reference code</strong> (shown after submission)</div>
                    </div>
                    <div class="momo-step">
                      <div class="momo-step-num momo-step-num-airtel">5</div>
                      <div class="momo-step-text">WhatsApp the payment confirmation screenshot to <strong>+256 700 000 000</strong></div>
                    </div>
                  </div>
                  <div class="momo-ref-box">
                    <div class="momo-ref-label">Send payment to</div>
                    <div class="momo-ref-value">0751 000 000 · DIP Admin</div>
                    <div class="momo-ref-note">Your reference code is generated when you submit the form</div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <button type="submit" class="btn-submit">
            Submit Listing & Get Payment Reference
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M3 8h10M9 4l4 4-4 4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
          </button>
          <p style="font-size:.75rem;color:var(--grey-400);text-align:center;margin-top:.7rem;line-height:1.5">
            By submitting you agree that this is a legitimate internship opportunity. False listings are permanently banned.
          </p>
        </div>
      </div>
    </form>
  </div>

  <!-- SIDEBAR -->
  <div class="post-sidebar">

    <!-- ORDER SUMMARY -->
    <div class="summary-card">
      <div class="summary-inner">
        <div class="summary-eyebrow">📋 Order Summary</div>
        <div class="summary-title" id="summary-tier-name"><?php echo $selected['name']; ?></div>
        <div class="summary-rows">
          <div class="summary-row">
            <span class="summary-row-key">Active period</span>
            <span class="summary-row-val" id="summary-days"><?php echo $selected['days']; ?> days</span>
          </div>
          <div class="summary-row">
            <span class="summary-row-key">Admin review</span>
            <span class="summary-row-val">Within 24 hours</span>
          </div>
          <div class="summary-row">
            <span class="summary-row-key">Applicants</span>
            <span class="summary-row-val">UICT students only</span>
          </div>
          <div class="summary-row">
            <span class="summary-row-key">Applications to</span>
            <span class="summary-row-val">Your inbox</span>
          </div>
        </div>
        <div class="summary-divider"></div>
        <div class="summary-total">
          <span class="summary-total-key">Total due</span>
          <span class="summary-total-val" id="summary-price"><?php echo $selected['label']; ?></span>
        </div>
        <div style="background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.1);border-radius:var(--r-md);padding:.9rem 1rem;font-size:.8rem;color:rgba(255,255,255,.55);line-height:1.55">
          💳 Payment via <strong style="color:#fff">MTN or Airtel Mobile Money</strong>. Confirmed manually by admin within 2 hours.
        </div>
        <div class="summary-note">No VAT · No hidden fees · One-time payment</div>
      </div>
    </div>

    <!-- PROCESS CARD -->
    <div class="process-card">
      <div class="process-title">What happens next</div>
      <div class="process-steps">
        <div class="process-step">
          <div class="process-step-num">1</div>
          <div>
            <div class="process-step-title">You submit this form</div>
            <div class="process-step-body">Your listing enters the review queue and you receive a unique payment reference.</div>
          </div>
        </div>
        <div class="process-step">
          <div class="process-step-num">2</div>
          <div>
            <div class="process-step-title">You pay via Mobile Money</div>
            <div class="process-step-body">Send the exact amount to our MTN or Airtel number using your reference code.</div>
          </div>
        </div>
        <div class="process-step">
          <div class="process-step-num">3</div>
          <div>
            <div class="process-step-title">Admin reviews & confirms</div>
            <div class="process-step-body">We verify your listing details and confirm your payment within 24 hours.</div>
          </div>
        </div>
        <div class="process-step">
          <div class="process-step-num">4</div>
          <div>
            <div class="process-step-title">Your listing goes live</div>
            <div class="process-step-body">All registered UICT students can see and apply. Applications go directly to your email.</div>
          </div>
        </div>
      </div>
    </div>

    <div class="alert alert-info" style="margin:0">
      <span>💡</span>
      <div style="font-size:.82rem;line-height:1.6">
        <strong>First time posting?</strong> See our <a href="/internhub/partners.php" style="color:var(--teal-700);font-weight:600">Partners page</a> for full details on what each package includes.
      </div>
    </div>

  </div>
</div>

<script>
const tiers = {
  standard: { name: 'Standard Listing', price: 'UGX 150,000', days: '60' },
  featured:  { name: 'Featured Listing',  price: 'UGX 300,000', days: '90' }
};

function selectTier(val, el) {
  document.querySelectorAll('.tier-option').forEach(o => {
    o.classList.remove('selected');
    o.querySelector('.tier-check').textContent = '';
  });
  el.classList.add('selected');
  el.querySelector('.tier-check').textContent = '✓';
  el.querySelector('input[type=radio]').checked = true;

  const t = tiers[val];
  document.getElementById('summary-tier-name').textContent = t.name;
  document.getElementById('summary-price').textContent = t.price;
  document.getElementById('summary-days').textContent = t.days + ' days';
  ['mtn-amount','mtn-amount-2','airtel-amount','airtel-amount-2'].forEach(id => {
    const el2 = document.getElementById(id);
    if(el2) el2.textContent = t.price;
  });
}

function toggleMomo(network) {
  const body    = document.getElementById(network+'-body');
  const toggle  = document.getElementById(network+'-toggle');
  const isOpen  = body.classList.contains('open');
  body.classList.toggle('open', !isOpen);
  toggle.classList.toggle('open', !isOpen);
}
</script>

<?php endif; ?>

<!-- FOOTER -->
<footer class="footer">
  <div class="footer-inner">
    <div class="footer-top">
      <div>
        <div class="footer-brand-name">Digital Internship Portal</div>
        <p class="footer-brand-desc">Uganda's structured internship platform — connecting UICT students with verified opportunities and preparing them to succeed.</p>
        <div class="footer-badge"><span class="footer-badge-dot"></span>UICT Nakawa · Kampala, Uganda</div>
      </div>
      <div class="footer-col">
        <div class="footer-col-title">Platform</div>
        <a href="/internhub/listings.php">Browse Internships</a>
        <a href="/internhub/readiness.php">Readiness Program</a>
        <a href="/internhub/partners.php">For Companies</a>
        <a href="/internhub/auth/register.php?type=student">Student Register</a>
        <a href="/internhub/auth/login.php">Sign In</a>
      </div>
      <div class="footer-col">
        <div class="footer-col-title">Company</div>
        <a href="/internhub/about.php">About Us</a>
        <a href="/internhub/contact.php">Contact</a>
        <a href="/internhub/contact.php#faq">FAQ</a>
        <a href="/internhub/admin/login.php">Admin</a>
      </div>
      <div class="footer-col">
        <div class="footer-col-title">Contact</div>
        <span>hello@internship.uict.ac.ug</span>
        <span>Computer Science Dept.</span>
        <span>UICT, Nakawa</span>
        <span>Kampala, Uganda</span>
      </div>
    </div>
    <div class="footer-bottom">
      <span>© 2025 Digital Internship Portal · UICT Nakawa · All Rights Reserved</span>
      <span>Built by Otuura Brian Oneka & Team · CS Diploma 2024/2025</span>
    </div>
  </div>
</footer>

</body>
</html>
