<?php
$activePage = 'partners';
$pageTitle  = 'Partner With Us';
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once 'includes/db.php';
require_once 'includes/header.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>For Companies — Digital Internship Portal</title>
<link rel="preconnect" href="https://fonts.googleapis.com"/>
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,300;0,9..144,600;0,9..144,700;1,9..144,300&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
<link rel="stylesheet" href="/internhub/css/style.css">
<style>
/* ── Reset & Tokens ── */
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{
  --teal-900:#062E22;--teal-800:#0B4D3B;--teal-700:#0E6B52;
  --teal-600:#128A69;--teal-400:#1DB885;--teal-200:#8EDFC5;
  --teal-100:#C5F0E3;--teal-50:#EDF9F5;
  --gold:#D4A017;--gold-lt:#FBF3D9;--gold-dk:#A67C00;
  --white:#FFFFFF;--off:#F8FAF9;
  --grey-100:#EEF1EF;--grey-200:#D8DFDB;
  --grey-400:#8A9E96;--grey-600:#4A5E56;--ink:#0D1F19;
  --shadow-sm:0 1px 4px rgba(6,46,34,.07);
  --shadow-md:0 4px 20px rgba(6,46,34,.10);
  --shadow-lg:0 12px 48px rgba(6,46,34,.13);
  --shadow-xl:0 24px 80px rgba(6,46,34,.16);
  --r-sm:6px;--r-md:12px;--r-lg:20px;--r-xl:32px;
  --ease:cubic-bezier(.4,0,.2,1);--t:.2s;
}
html{scroll-behavior:smooth;font-size:16px}
body{font-family:'Plus Jakarta Sans',sans-serif;background:var(--white);color:var(--ink);overflow-x:hidden;-webkit-font-smoothing:antialiased}
img{display:block;max-width:100%}
a{text-decoration:none;color:inherit}
::-webkit-scrollbar{width:5px}
::-webkit-scrollbar-track{background:var(--off)}
::-webkit-scrollbar-thumb{background:var(--grey-200);border-radius:99px}

/* ── NAV ── */
.nav{position:sticky;top:0;z-index:200;height:68px;display:flex;align-items:center;justify-content:space-between;padding:0 2.5rem;background:rgba(255,255,255,.95);backdrop-filter:blur(14px);border-bottom:1px solid var(--grey-100);box-shadow:var(--shadow-sm)}
.nav-brand{display:flex;align-items:center;gap:10px;font-family:'Fraunces',serif;font-size:1.1rem;font-weight:700;color:var(--teal-800);letter-spacing:-.01em}
.nav-brand-dot{width:8px;height:8px;border-radius:50%;background:var(--teal-600);box-shadow:0 0 0 3px var(--teal-100)}
.nav-links{display:flex;align-items:center;gap:4px}
.nav-links a{font-size:.875rem;font-weight:500;color:var(--grey-600);padding:6px 13px;border-radius:var(--r-sm);transition:color var(--t) var(--ease),background var(--t) var(--ease)}
.nav-links a:hover{color:var(--ink);background:var(--off)}
.nav-links a.active{color:var(--teal-700);font-weight:600}
.nav-cta{background:var(--teal-800)!important;color:#fff!important;font-weight:600!important;border-radius:var(--r-sm)!important;padding:8px 18px!important;box-shadow:0 2px 8px rgba(11,77,59,.28)!important;transition:all var(--t) var(--ease)!important}
.nav-cta:hover{background:var(--teal-900)!important;transform:translateY(-1px)!important}

/* ── SHARED LAYOUT ── */
.container{max-width:1200px;margin:0 auto;padding:0 2.5rem}
.section{padding:6rem 0}
.s-eyebrow{font-size:.7rem;font-weight:700;letter-spacing:.16em;text-transform:uppercase;color:var(--teal-600);margin-bottom:.5rem}
.s-title{font-family:'Fraunces',serif;font-size:clamp(1.8rem,3.5vw,2.6rem);font-weight:700;color:var(--ink);line-height:1.12;letter-spacing:-.025em;margin-bottom:.8rem}
.s-sub{font-size:1rem;color:var(--grey-600);line-height:1.72;max-width:560px}

/* ── BUTTONS ── */
.btn-solid{display:inline-flex;align-items:center;gap:8px;background:var(--gold);color:var(--ink);font-family:'Plus Jakarta Sans',sans-serif;font-weight:700;font-size:.9rem;padding:13px 28px;border-radius:var(--r-sm);border:none;cursor:pointer;box-shadow:0 4px 16px rgba(212,160,23,.35);transition:all var(--t) var(--ease)}
.btn-solid:hover{background:var(--gold-dk);transform:translateY(-2px)}
.btn-primary{display:inline-flex;align-items:center;gap:7px;background:var(--teal-800);color:#fff;font-family:'Plus Jakarta Sans',sans-serif;font-weight:600;font-size:.9rem;padding:12px 24px;border-radius:var(--r-sm);border:none;cursor:pointer;box-shadow:0 2px 8px rgba(11,77,59,.25);transition:all var(--t) var(--ease)}
.btn-primary:hover{background:var(--teal-900);transform:translateY(-1px)}
.btn-outline{display:inline-flex;align-items:center;gap:7px;background:transparent;color:var(--teal-800);font-family:'Plus Jakarta Sans',sans-serif;font-weight:600;font-size:.9rem;padding:12px 24px;border-radius:var(--r-sm);border:1.5px solid var(--teal-800);transition:all var(--t) var(--ease)}
.btn-outline:hover{background:var(--teal-50)}
.btn-ghost-light{display:inline-flex;align-items:center;gap:8px;background:rgba(255,255,255,.1);color:#fff;font-family:'Plus Jakarta Sans',sans-serif;font-weight:500;font-size:.9rem;padding:13px 28px;border-radius:var(--r-sm);border:1px solid rgba(255,255,255,.2);transition:all var(--t) var(--ease)}
.btn-ghost-light:hover{background:rgba(255,255,255,.18);border-color:rgba(255,255,255,.35)}

/* ── PAGE HERO ── */
.page-hero{background:var(--teal-900);position:relative;overflow:hidden;padding:6rem 0 5rem}
.page-hero::before{content:'';position:absolute;inset:0;background:radial-gradient(ellipse 70% 60% at 60% 40%,rgba(18,138,105,.3) 0%,transparent 70%),radial-gradient(ellipse 40% 50% at 10% 80%,rgba(11,77,59,.4) 0%,transparent 60%);pointer-events:none}
.page-hero::after{content:'';position:absolute;inset:0;background-image:linear-gradient(rgba(255,255,255,.025) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.025) 1px,transparent 1px);background-size:52px 52px;pointer-events:none}
.page-hero-inner{position:relative;z-index:1;max-width:800px}
.page-hero-badge{display:inline-flex;align-items:center;gap:8px;font-size:.72rem;font-weight:600;letter-spacing:.14em;text-transform:uppercase;color:var(--teal-400);background:rgba(29,184,133,.12);border:1px solid rgba(29,184,133,.25);padding:5px 14px;border-radius:99px;margin-bottom:1.5rem}
.page-hero-title{font-family:'Fraunces',serif;font-size:clamp(2.4rem,5vw,3.6rem);font-weight:700;line-height:1.08;letter-spacing:-.03em;color:#fff;margin-bottom:1.2rem}
.page-hero-title em{font-style:italic;color:var(--teal-400)}
.page-hero-body{font-size:1.05rem;color:rgba(255,255,255,.68);line-height:1.78;max-width:600px;margin-bottom:2rem;font-weight:300}
.hero-actions{display:flex;gap:.9rem;flex-wrap:wrap}

/* ── TRUST BAR ── */
.trust-bar{background:var(--white);border-bottom:1px solid var(--grey-100);padding:2rem 0}
.trust-bar-inner{display:flex;align-items:center;justify-content:space-between;gap:2rem;flex-wrap:wrap}
.trust-bar-label{font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.12em;color:var(--grey-400);white-space:nowrap}
.trust-bar-items{display:flex;gap:2rem;align-items:center;flex-wrap:wrap}
.trust-item{display:flex;align-items:center;gap:8px;font-size:.85rem;font-weight:500;color:var(--grey-600)}
.trust-item-check{width:20px;height:20px;border-radius:50%;background:var(--teal-50);border:1px solid var(--teal-100);display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:.65rem;color:var(--teal-700);font-weight:700}

/* ── WHY PARTNER ── */
.why-section{background:var(--off);border-top:1px solid var(--grey-100)}
.why-grid{display:grid;grid-template-columns:1fr 1fr;gap:5rem;align-items:center}
.why-left .s-sub{margin-bottom:2rem}
.why-stat-row{display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-top:2rem}
.why-stat{background:var(--white);border:1px solid var(--grey-200);border-radius:var(--r-md);padding:1.3rem;transition:all var(--t) var(--ease)}
.why-stat:hover{border-color:var(--teal-200);box-shadow:var(--shadow-md)}
.why-stat-num{font-family:'Fraunces',serif;font-size:2rem;font-weight:700;color:var(--teal-700);line-height:1;margin-bottom:4px}
.why-stat-num span{color:var(--teal-400)}
.why-stat-label{font-size:.8rem;color:var(--grey-600);line-height:1.5}
.why-cards{display:flex;flex-direction:column;gap:1rem}
.why-card{background:var(--white);border:1px solid var(--grey-200);border-radius:var(--r-md);padding:1.4rem;display:flex;gap:1.2rem;align-items:flex-start;transition:all var(--t) var(--ease)}
.why-card:hover{border-color:var(--teal-200);box-shadow:var(--shadow-md);transform:translateX(4px)}
.why-card-icon{width:44px;height:44px;border-radius:var(--r-sm);background:var(--teal-50);border:1px solid var(--teal-100);display:flex;align-items:center;justify-content:center;font-size:1.25rem;flex-shrink:0}
.why-card-title{font-family:'Fraunces',serif;font-weight:600;font-size:.98rem;color:var(--ink);margin-bottom:.3rem}
.why-card-body{font-size:.86rem;color:var(--grey-600);line-height:1.65}

/* ── HOW IT WORKS ── */
.hiw-section{background:var(--white);border-top:1px solid var(--grey-100)}
.hiw-steps{display:grid;grid-template-columns:repeat(4,1fr);gap:0;margin-top:4rem;position:relative}
.hiw-steps::before{content:'';position:absolute;top:28px;left:8%;right:8%;height:1px;background:var(--grey-200);z-index:0}
.hiw-step{text-align:center;padding:0 1.5rem;position:relative;z-index:1}
.hiw-num{width:56px;height:56px;border-radius:50%;background:var(--white);border:2px solid var(--grey-200);display:flex;align-items:center;justify-content:center;margin:0 auto 1.5rem;box-shadow:var(--shadow-sm);font-family:'Fraunces',serif;font-weight:700;font-size:1.1rem;color:var(--teal-700);transition:all var(--t) var(--ease)}
.hiw-step:hover .hiw-num{border-color:var(--teal-600);box-shadow:0 0 0 5px var(--teal-50)}
.hiw-title{font-family:'Fraunces',serif;font-weight:600;font-size:.98rem;color:var(--ink);margin-bottom:.5rem}
.hiw-body{font-size:.83rem;color:var(--grey-600);line-height:1.6}

/* ── LISTING TIERS ── */
.tiers-section{background:var(--teal-900);position:relative;overflow:hidden;padding:6rem 0}
.tiers-section::before{content:'';position:absolute;inset:0;background:radial-gradient(ellipse 60% 70% at 70% 50%,rgba(18,138,105,.28) 0%,transparent 65%),radial-gradient(ellipse 40% 50% at 5% 30%,rgba(11,77,59,.5) 0%,transparent 55%);pointer-events:none}
.tiers-inner{position:relative;z-index:1}
.tiers-header{text-align:center;margin-bottom:3.5rem}
.tiers-header .s-eyebrow{color:var(--teal-400)}
.tiers-header .s-title{color:#fff}
.tiers-header .s-sub{color:rgba(255,255,255,.6);margin:0 auto}
.tiers-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:1.5rem;align-items:start}
.tier-card{background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);border-radius:var(--r-xl);padding:2.2rem;transition:all var(--t) var(--ease)}
.tier-card:hover{background:rgba(255,255,255,.09);transform:translateY(-4px)}
.tier-card-featured{background:var(--white);border-color:var(--white)}
.tier-card-featured:hover{background:var(--white)}
.tier-badge{display:inline-block;font-size:.68rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;background:var(--gold);color:var(--ink);padding:3px 10px;border-radius:99px;margin-bottom:1.2rem}
.tier-name{font-family:'Fraunces',serif;font-weight:700;font-size:1.2rem;color:#fff;margin-bottom:.4rem}
.tier-card-featured .tier-name{color:var(--ink)}
.tier-desc{font-size:.85rem;color:rgba(255,255,255,.55);margin-bottom:1.5rem;line-height:1.6}
.tier-card-featured .tier-desc{color:var(--grey-600)}
.tier-price{display:flex;align-items:flex-end;gap:4px;margin-bottom:.3rem}
.tier-currency{font-size:.9rem;color:rgba(255,255,255,.5);margin-bottom:.3rem}
.tier-card-featured .tier-currency{color:var(--grey-400)}
.tier-num{font-family:'Fraunces',serif;font-size:2.8rem;font-weight:700;color:#fff;line-height:1}
.tier-card-featured .tier-num{color:var(--ink)}
.tier-period{font-size:.82rem;color:rgba(255,255,255,.4);margin-bottom:1.5rem}
.tier-card-featured .tier-period{color:var(--grey-400)}
.tier-divider{height:1px;background:rgba(255,255,255,.1);margin-bottom:1.5rem}
.tier-card-featured .tier-divider{background:var(--grey-100)}
.tier-features{list-style:none;display:flex;flex-direction:column;gap:.7rem;margin-bottom:2rem}
.tier-features li{font-size:.875rem;color:rgba(255,255,255,.75);display:flex;gap:9px;align-items:flex-start;line-height:1.5}
.tier-card-featured .tier-features li{color:var(--grey-600)}
.tier-features li::before{content:'✓';color:var(--teal-400);font-weight:700;flex-shrink:0;margin-top:1px}
.tier-card-featured .tier-features li::before{color:var(--teal-700)}
.tier-features li.na{opacity:.4}
.tier-features li.na::before{content:'–'}
.btn-tier-primary{width:100%;padding:12px;background:var(--gold);color:var(--ink);font-family:'Plus Jakarta Sans',sans-serif;font-weight:700;font-size:.9rem;border:none;border-radius:var(--r-sm);cursor:pointer;box-shadow:0 3px 12px rgba(212,160,23,.3);transition:all var(--t) var(--ease);text-align:center;display:block}
.btn-tier-primary:hover{background:var(--gold-dk);transform:translateY(-1px)}
.btn-tier-ghost{width:100%;padding:12px;background:transparent;color:#fff;font-family:'Plus Jakarta Sans',sans-serif;font-weight:600;font-size:.9rem;border:1px solid rgba(255,255,255,.25);border-radius:var(--r-sm);cursor:pointer;transition:all var(--t) var(--ease);text-align:center;display:block}
.btn-tier-ghost:hover{background:rgba(255,255,255,.1)}
.btn-tier-outline{width:100%;padding:12px;background:transparent;color:var(--teal-800);font-family:'Plus Jakarta Sans',sans-serif;font-weight:600;font-size:.9rem;border:1.5px solid var(--teal-800);border-radius:var(--r-sm);cursor:pointer;transition:all var(--t) var(--ease);text-align:center;display:block}
.btn-tier-outline:hover{background:var(--teal-50)}

/* ── WHAT YOU GET ── */
.get-section{background:var(--off);border-top:1px solid var(--grey-100)}
.get-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:1.25rem;margin-top:3.5rem}
.get-card{background:var(--white);border:1px solid var(--grey-200);border-radius:var(--r-lg);padding:2rem;transition:all .25s var(--ease);position:relative;overflow:hidden}
.get-card::after{content:'';position:absolute;bottom:0;left:0;right:0;height:3px;background:linear-gradient(90deg,var(--teal-600),var(--teal-400));transform:scaleX(0);transform-origin:left;transition:transform .25s var(--ease)}
.get-card:hover{transform:translateY(-4px);box-shadow:var(--shadow-lg)}
.get-card:hover::after{transform:scaleX(1)}
.get-icon{font-size:2rem;margin-bottom:1.2rem}
.get-title{font-family:'Fraunces',serif;font-weight:700;font-size:1.05rem;color:var(--ink);margin-bottom:.6rem}
.get-body{font-size:.875rem;color:var(--grey-600);line-height:1.68}

/* ── PROCESS ── */
.process-section{background:var(--white);border-top:1px solid var(--grey-100)}

/* ── REGISTER CTA ── */
.register-section{background:var(--white);border-top:1px solid var(--grey-100);padding:6rem 0}
.register-card{background:var(--teal-800);border-radius:var(--r-xl);padding:4rem;display:grid;grid-template-columns:1fr 1fr;gap:4rem;align-items:center;position:relative;overflow:hidden}
.register-card::before{content:'';position:absolute;top:-30%;right:-5%;width:350px;height:350px;border-radius:50%;background:radial-gradient(circle,rgba(29,184,133,.15),transparent 70%);pointer-events:none}
.register-eyebrow{font-size:.7rem;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:var(--teal-400);margin-bottom:.8rem}
.register-title{font-family:'Fraunces',serif;font-size:clamp(1.7rem,3vw,2.4rem);font-weight:700;color:#fff;line-height:1.12;margin-bottom:.8rem;letter-spacing:-.02em}
.register-body{font-size:.95rem;color:rgba(255,255,255,.65);line-height:1.72;margin-bottom:1.8rem;font-weight:300}
.register-actions{display:flex;gap:.9rem;flex-wrap:wrap}
.register-info{display:flex;flex-direction:column;gap:1rem}
.register-info-item{display:flex;align-items:center;gap:12px;background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.1);border-radius:var(--r-md);padding:1.1rem 1.3rem}
.register-info-icon{font-size:1.3rem;flex-shrink:0}
.register-info-label{font-size:.75rem;font-weight:600;color:rgba(255,255,255,.45);text-transform:uppercase;letter-spacing:.06em;margin-bottom:2px}
.register-info-value{font-family:'Fraunces',serif;font-weight:600;font-size:.92rem;color:#fff}

/* ── TESTIMONIAL ── */
.testi-section{background:var(--off);border-top:1px solid var(--grey-100)}
.testi-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:1.5rem;margin-top:3.5rem}
.testi{background:var(--white);border:1px solid var(--grey-100);border-radius:var(--r-lg);padding:2rem;display:flex;flex-direction:column;transition:all var(--t) var(--ease)}
.testi:hover{box-shadow:var(--shadow-md);border-color:var(--grey-200);transform:translateY(-3px)}
.testi-stars{color:var(--gold);font-size:.85rem;letter-spacing:2px;margin-bottom:1rem}
.testi-text{font-size:.92rem;color:var(--grey-600);line-height:1.78;margin-bottom:1.8rem;flex:1;font-style:italic}
.testi-author{display:flex;align-items:center;gap:12px}
.testi-avatar{width:40px;height:40px;border-radius:50%;flex-shrink:0;display:flex;align-items:center;justify-content:center;font-family:'Fraunces',serif;font-weight:700;font-size:.82rem;color:#fff}
.testi-name{font-family:'Fraunces',serif;font-weight:600;font-size:.9rem;color:var(--ink)}
.testi-role{font-size:.75rem;color:var(--grey-400);margin-top:1px}

/* ── FOOTER ── */
.footer{background:#040F0A;padding:5rem 0 2.5rem}
.footer-top{display:grid;grid-template-columns:2.5fr 1fr 1fr 1.2fr;gap:3rem;padding-bottom:3rem;border-bottom:1px solid rgba(255,255,255,.07);margin-bottom:2rem}
.footer-brand-name{font-family:'Fraunces',serif;font-size:1.1rem;font-weight:700;color:#fff;margin-bottom:.8rem}
.footer-brand-desc{font-size:.85rem;color:rgba(255,255,255,.4);line-height:1.7;margin-bottom:1.2rem;max-width:260px}
.footer-badge{display:inline-flex;align-items:center;gap:7px;background:rgba(29,184,133,.1);border:1px solid rgba(29,184,133,.2);border-radius:99px;padding:4px 12px;font-size:.72rem;font-weight:600;color:var(--teal-400)}
.footer-badge-dot{width:5px;height:5px;border-radius:50%;background:var(--teal-400)}
.footer-col-title{font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.12em;color:rgba(255,255,255,.3);margin-bottom:1.2rem}
.footer-col a,.footer-col span{display:block;font-size:.86rem;color:rgba(255,255,255,.5);text-decoration:none;margin-bottom:.55rem;transition:color var(--t) var(--ease)}
.footer-col a:hover{color:rgba(255,255,255,.9)}
.footer-bottom{display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem;font-size:.78rem;color:rgba(255,255,255,.22)}

/* ── RESPONSIVE ── */
@media(max-width:1024px){
  .why-grid{grid-template-columns:1fr;gap:2.5rem}
  .tiers-grid{grid-template-columns:1fr 1fr}
  .get-grid{grid-template-columns:1fr 1fr}
  .hiw-steps{grid-template-columns:1fr 1fr}
  .hiw-steps::before{display:none}
  .register-card{grid-template-columns:1fr;gap:2.5rem;padding:2.5rem}
  .testi-grid{grid-template-columns:1fr 1fr}
  .footer-top{grid-template-columns:1fr 1fr;gap:2rem}
}
@media(max-width:768px){
  .nav{padding:0 1.25rem}
  .nav-links a:not(.nav-cta){display:none}
  .section{padding:4rem 0}
  .container{padding:0 1.25rem}
  .tiers-grid{grid-template-columns:1fr}
  .get-grid{grid-template-columns:1fr}
  .hiw-steps{grid-template-columns:1fr 1fr}
  .testi-grid{grid-template-columns:1fr}
  .footer-top{grid-template-columns:1fr}
  .trust-bar-inner{flex-direction:column;align-items:flex-start;gap:1rem}
  .why-stat-row{grid-template-columns:1fr}
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
    <a href="/internhub/partners.php" class="active">For Companies</a>
    <a href="/internhub/about.php">About</a>
    <?php if(isset($_SESSION['user_id'])): ?>
      <a href="/internhub/auth/dashboard.php"><?php echo htmlspecialchars(explode(' ',$_SESSION['user_name'])[0]); ?> ▾</a>
      <a href="/internhub/auth/actions/logout.php" class="nav-cta">Log Out</a>
    <?php else: ?>
      <a href="/internhub/auth/login.php">Sign In</a>
      <a href="/internhub/auth/register.php?type=company" class="nav-cta">Register Company</a>
    <?php endif; ?>
  </div>
</nav>

<!-- PAGE HERO -->
<section class="page-hero">
  <div class="container">
    <div class="page-hero-inner">
      <div class="page-hero-badge">🤝 Company Partnerships</div>
      <h1 class="page-hero-title">
        Stop sorting through<br/>
        noise. Get <em>qualified</em><br/>
        UICT talent.
      </h1>
      <p class="page-hero-body">
        Every listing on the Digital Internship Portal reaches verified UICT Computer
        Science students — institution-confirmed, admin-reviewed, and many
        Readiness-certified before they apply. Post once. Hire with confidence.
      </p>
      <div class="hero-actions">
        <a href="#tiers" class="btn-solid">
          View Listing Packages
          <svg width="14" height="14" viewBox="0 0 14 14" fill="none"><path d="M3 7h8M8 3.5L11.5 7 8 10.5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </a>
        <a href="/internhub/contact.php" class="btn-ghost-light">Talk to Us First</a>
      </div>
    </div>
  </div>
</section>

<!-- TRUST BAR -->
<section class="trust-bar">
  <div class="container">
    <div class="trust-bar-inner">
      <span class="trust-bar-label">Every listing includes</span>
      <div class="trust-bar-items">
        <?php
        $trust=[
          'Admin-reviewed before going live',
          'UICT-only verified applicants',
          'Direct student email applications',
          'Active for 60–90 days',
          'Readiness-certified candidates available',
        ];
        foreach($trust as $t):
        ?>
        <div class="trust-item">
          <div class="trust-item-check">✓</div>
          <span><?php echo $t; ?></span>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>

<!-- WHY PARTNER -->
<section class="section why-section">
  <div class="container">
    <div class="why-grid">
      <div class="why-left">
        <div class="s-eyebrow">Why Partner With Us</div>
        <h2 class="s-title">We are not a job board.<br/>We are a pipeline.</h2>
        <p class="s-sub">
          BrighterMonday gives you thousands of applications from anyone, anywhere.
          We give you a shortlist of verified UICT students — institution-confirmed,
          field-matched, and many professionally prepared before they even apply.
        </p>
        <div class="why-stat-row">
          <?php
          $stats=[
            ['2,000+','UICT students in the talent pool'],
            ['100%','Listings admin-reviewed before going live'],
            ['UGX 0','Cost to students — protecting application quality'],
            ['60–90','Days your listing stays active on the platform'],
          ];
          foreach($stats as [$num,$label]):
          ?>
          <div class="why-stat">
            <div class="why-stat-num"><?php echo $num; ?></div>
            <div class="why-stat-label"><?php echo $label; ?></div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
      <div class="why-cards">
        <?php
        $whys=[
          ['🎯','Targeted, not scattered','Your listing does not go to the general public. It reaches verified UICT CS and IT students — exactly the profile most Kampala-based tech and finance companies are looking for.'],
          ['✅','Readiness-certified candidates','Students who have completed the Readiness Program arrive with CVs, interview skills, and professional conduct already in place. Reduced onboarding. Faster contribution.'],
          ['🔒','Admin-moderated quality','Before any listing goes live, our team reviews it for legitimacy — company contact confirmed, role details verified. No fake listings. No time wasters.'],
          ['📈','Build your future talent pipeline','The intern you take this semester is your full-time hire in two years. Partner companies get early, repeated access to Uganda\'s best emerging ICT talent.'],
          ['📊','Simple process, professional results','Post your listing, we review and publish it, applications arrive directly to your inbox. No dashboard to manage. No algorithm to game.'],
        ];
        foreach($whys as [$icon,$title,$body]):
        ?>
        <div class="why-card">
          <div class="why-card-icon"><?php echo $icon; ?></div>
          <div>
            <div class="why-card-title"><?php echo $title; ?></div>
            <div class="why-card-body"><?php echo $body; ?></div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>

<!-- HOW IT WORKS -->
<section class="section hiw-section process-section">
  <div class="container">
    <div style="text-align:center">
      <div class="s-eyebrow" style="display:inline-block">Process</div>
      <h2 class="s-title">From registration to applications in 4 steps</h2>
      <p class="s-sub" style="margin:0 auto 0">Simple and fast. Your listing can be live within 24 hours of submission.</p>
    </div>
    <div class="hiw-steps">
      <div class="hiw-step">
        <div class="hiw-num">01</div>
        <h3 class="hiw-title">Register Your Organisation</h3>
        <p class="hiw-body">Create a company account with your official business details. Takes under five minutes.</p>
      </div>
      <div class="hiw-step">
        <div class="hiw-num">02</div>
        <h3 class="hiw-title">Submit Your Listing</h3>
        <p class="hiw-body">Fill in the internship details — role, duration, requirements, pay type, and application deadline.</p>
      </div>
      <div class="hiw-step">
        <div class="hiw-num">03</div>
        <h3 class="hiw-title">Admin Review</h3>
        <p class="hiw-body">Our team reviews your listing within 24 hours, confirms your contact, and publishes it to the platform.</p>
      </div>
      <div class="hiw-step">
        <div class="hiw-num">04</div>
        <h3 class="hiw-title">Receive Applications</h3>
        <p class="hiw-body">Verified student applications arrive directly to your inbox. Shortlist, interview, and place.</p>
      </div>
    </div>
  </div>
</section>

<!-- LISTING TIERS / PRICING -->
<section class="tiers-section" id="tiers">
  <div class="container tiers-inner">
    <div class="tiers-header">
      <div class="s-eyebrow">Listing Packages</div>
      <h2 class="s-title">Simple, transparent pricing</h2>
      <p class="s-sub">One listing. One cohort of qualified applicants. No subscriptions. No hidden fees.</p>
    </div>
    <div class="tiers-grid">

      <!-- Standard -->
      <div class="tier-card">
        <div class="tier-name">Standard Listing</div>
        <div class="tier-desc">One internship opportunity, published and verified, active for 60 days.</div>
        <div class="tier-price">
          <span class="tier-currency">UGX</span>
          <span class="tier-num">150,000</span>
        </div>
        <div class="tier-period">per listing · 60-day active period</div>
        <div class="tier-divider"></div>
        <ul class="tier-features">
          <li>1 internship listing slot</li>
          <li>Admin review and verification</li>
          <li>Visible to all registered UICT students</li>
          <li>Applications to your company inbox</li>
          <li>Active for 60 days</li>
          <li class="na">Featured placement in search results</li>
          <li class="na">Readiness-certified applicants filter</li>
          <li class="na">Logo badge on listing</li>
        </ul>
        <a href="/internhub/auth/register.php?type=company" class="btn-tier-ghost">Get Started</a>
      </div>

      <!-- Featured (highlighted) -->
      <div class="tier-card tier-card-featured">
        <div class="tier-badge">Most Chosen</div>
        <div class="tier-name">Featured Listing</div>
        <div class="tier-desc">Priority placement, Readiness-certified filter, and your company logo badge.</div>
        <div class="tier-price">
          <span class="tier-currency" style="color:var(--grey-400)">UGX</span>
          <span class="tier-num">300,000</span>
        </div>
        <div class="tier-period">per listing · 90-day active period</div>
        <div class="tier-divider"></div>
        <ul class="tier-features">
          <li>1 internship listing slot</li>
          <li>Admin review and verification</li>
          <li>Visible to all registered UICT students</li>
          <li>Applications to your company inbox</li>
          <li>Active for 90 days</li>
          <li>Featured at top of search results</li>
          <li>Readiness-certified applicants filter</li>
          <li>Company logo badge on listing</li>
        </ul>
        <a href="/internhub/auth/register.php?type=company" class="btn-tier-primary">Post a Featured Listing</a>
      </div>

      <!-- Annual Partner -->
      <div class="tier-card">
        <div class="tier-name">Annual Partner</div>
        <div class="tier-desc">Multiple listings per year, dedicated support, and co-branding with UICT.</div>
        <div class="tier-price">
          <span class="tier-num" style="font-size:2rem;color:#fff">Custom</span>
        </div>
        <div class="tier-period">annual agreement · volume rates apply</div>
        <div class="tier-divider"></div>
        <ul class="tier-features">
          <li>Up to 6 listings per year</li>
          <li>Priority admin review (same-day)</li>
          <li>All Featured tier benefits</li>
          <li>Co-branded partner page on portal</li>
          <li>Department-level placement reporting</li>
          <li>Early access to new cohort graduates</li>
          <li>Dedicated account contact</li>
          <li>Annual partnership certificate</li>
        </ul>
        <a href="/internhub/contact.php" class="btn-tier-ghost">Contact for Pricing</a>
      </div>

    </div>
  </div>
</section>

<!-- WHAT YOU GET -->
<section class="section get-section">
  <div class="container">
    <div style="text-align:center">
      <div class="s-eyebrow" style="display:inline-block">What's Included</div>
      <h2 class="s-title">What every listing gets you</h2>
      <p class="s-sub" style="margin:0 auto">Regardless of tier, every company on the portal gets a structured, professional experience.</p>
    </div>
    <div class="get-grid">
      <?php
      $gets=[
        ['🎯','Only UICT students apply','Every applicant is a registered UICT student — identity-verified against their institutional email. No random public applicants.'],
        ['🔒','Your listing is verified first','We call your contact person, confirm the internship details, and only publish when we are satisfied it is legitimate. Your brand is protected.'],
        ['📥','Applications go straight to you','No portal inbox to manage. Student applications — including their CV and contact details — go directly to the email you register with.'],
        ['⏱','Live within 24 hours','Standard review turnaround is one business day. Featured and Annual partners get same-day review.'],
        ['📋','You set the requirements','Specify field (Web, Data, Cyber, Networking), duration, pay type, and any prerequisites. Students self-filter before applying.'],
        ['🏆','Access to Readiness-certified students','Featured and Annual partners can filter to see only students who have completed the Readiness Program — pre-screened and professionally prepared.'],
      ];
      foreach($gets as [$icon,$title,$body]):
      ?>
      <div class="get-card">
        <div class="get-icon"><?php echo $icon; ?></div>
        <div class="get-title"><?php echo $title; ?></div>
        <div class="get-body"><?php echo $body; ?></div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- TESTIMONIALS -->
<section class="section testi-section">
  <div class="container">
    <div style="text-align:center">
      <div class="s-eyebrow" style="display:inline-block">From Our Partners</div>
      <h2 class="s-title">What companies are saying</h2>
    </div>
    <div class="testi-grid">
      <?php
      $testis=[
        ['RN','#B45309','Rebecca Nakato','HR Manager · SafeBoda Technology',
         'We posted a listing and within a week had five well-prepared applications from UICT students. The quality was genuinely impressive — one of them is still with us full-time.'],
        ['JO','#0B4D3B','James Onyango','CTO · Kampala Fintech Ltd',
         'The admin review process gave us confidence. We knew every applicant was a real, verified UICT student. No noise. Just relevant candidates we could actually shortlist.'],
        ['AM','#7C3AED','Amina Mugisha','Internship Coordinator · DFCU Bank',
         'What stood out was that the students who came through the Readiness Program already understood corporate communication. That is rare at intern level in Uganda.'],
      ];
      foreach($testis as [$init,$col,$name,$role,$text]):
      ?>
      <div class="testi">
        <div class="testi-stars">★★★★★</div>
        <p class="testi-text">"<?php echo $text; ?>"</p>
        <div class="testi-author">
          <div class="testi-avatar" style="background:<?php echo $col; ?>"><?php echo $init; ?></div>
          <div>
            <div class="testi-name"><?php echo $name; ?></div>
            <div class="testi-role"><?php echo $role; ?></div>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- REGISTER CTA -->
<section class="register-section">
  <div class="container">
    <div class="register-card">
      <div>
        <div class="register-eyebrow">Ready to Post?</div>
        <h2 class="register-title">Your next intern is already on this platform.</h2>
        <p class="register-body">
          Register your organisation today. Your listing can be live within
          24 hours. Payment via Mobile Money — no bank transfer hassle.
        </p>
        <div class="register-actions">
          <a href="/internhub/auth/register.php?type=company" class="btn-solid">Register Your Organisation</a>
          <a href="/internhub/contact.php" class="btn-ghost-light">Speak to Us First</a>
        </div>
      </div>
      <div class="register-info">
        <?php
        $info=[
          ['💳','Payment','UGX 150,000 – 300,000 · MTN or Airtel Mobile Money'],
          ['⏱','Review Time','Within 24 hours of submission (Featured: same day)'],
          ['📍','Who Can Post','Any registered Ugandan organisation with a valid contact'],
          ['📋','Listing Duration','60 days (Standard) · 90 days (Featured)'],
          ['📞','Questions?','Call or WhatsApp: +256 700 000 000'],
        ];
        foreach($info as [$icon,$label,$value]):
        ?>
        <div class="register-info-item">
          <div class="register-info-icon"><?php echo $icon; ?></div>
          <div>
            <div class="register-info-label"><?php echo $label; ?></div>
            <div class="register-info-value"><?php echo $value; ?></div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>

<!-- FOOTER -->
<footer class="footer">
  <div class="container">
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
