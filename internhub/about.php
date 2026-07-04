<?php
$activePage = 'about';
$pageTitle  = 'About Us';
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once 'includes/db.php';
require_once 'includes/header.php';

$totalListings  = $pdo->query("SELECT COUNT(*) FROM internships WHERE status='approved'")->fetchColumn();
$totalCompanies = $pdo->query("SELECT COUNT(DISTINCT company) FROM internships WHERE status='approved'")->fetchColumn();
$totalStudents  = $pdo->query("SELECT COUNT(*) FROM users WHERE role='student'")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>About — Digital Internship Portal</title>
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
  --grey-100:#EEF1EF;--grey-200:#D8DFDB;
  --grey-400:#8A9E96;--grey-600:#4A5E56;--ink:#0D1F19;
  --shadow-sm:0 1px 4px rgba(6,46,34,.07);
  --shadow-md:0 4px 20px rgba(6,46,34,.10);
  --shadow-lg:0 12px 48px rgba(6,46,34,.13);
  --shadow-xl:0 24px 80px rgba(6,46,34,.16);
  --r-sm:6px;--r-md:12px;--r-lg:20px;--r-xl:32px;
  --ease:cubic-bezier(.4,0,.2,1);--t:.2s;
}
html{scroll-behavior:smooth}
body{font-family:'Plus Jakarta Sans',sans-serif;background:var(--white);color:var(--ink);overflow-x:hidden;-webkit-font-smoothing:antialiased}
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

/* SHARED */
.container{max-width:1200px;margin:0 auto;padding:0 2.5rem}
.section{padding:6rem 0}
.s-eyebrow{font-size:.7rem;font-weight:700;letter-spacing:.16em;text-transform:uppercase;color:var(--teal-600);margin-bottom:.5rem}
.s-title{font-family:'Fraunces',serif;font-size:clamp(1.8rem,3.5vw,2.6rem);font-weight:700;color:var(--ink);line-height:1.12;letter-spacing:-.025em;margin-bottom:.8rem}
.s-sub{font-size:1rem;color:var(--grey-600);line-height:1.72;max-width:560px}
.btn-primary{display:inline-flex;align-items:center;gap:7px;background:var(--teal-800);color:#fff;font-family:'Plus Jakarta Sans',sans-serif;font-weight:600;font-size:.9rem;padding:12px 24px;border-radius:var(--r-sm);border:none;cursor:pointer;box-shadow:0 2px 8px rgba(11,77,59,.25);transition:all var(--t) var(--ease)}
.btn-primary:hover{background:var(--teal-900);transform:translateY(-1px)}
.btn-outline{display:inline-flex;align-items:center;gap:7px;background:transparent;color:var(--teal-800);font-family:'Plus Jakarta Sans',sans-serif;font-weight:600;font-size:.9rem;padding:12px 24px;border-radius:var(--r-sm);border:1.5px solid var(--teal-800);transition:all var(--t) var(--ease)}
.btn-outline:hover{background:var(--teal-50)}
.btn-ghost-light{display:inline-flex;align-items:center;gap:8px;background:rgba(255,255,255,.1);color:#fff;font-family:'Plus Jakarta Sans',sans-serif;font-weight:500;font-size:.9rem;padding:13px 28px;border-radius:var(--r-sm);border:1px solid rgba(255,255,255,.2);transition:all var(--t) var(--ease)}
.btn-ghost-light:hover{background:rgba(255,255,255,.18)}
.btn-gold{display:inline-flex;align-items:center;gap:8px;background:var(--gold);color:var(--ink);font-family:'Plus Jakarta Sans',sans-serif;font-weight:700;font-size:.9rem;padding:13px 28px;border-radius:var(--r-sm);border:none;cursor:pointer;box-shadow:0 4px 16px rgba(212,160,23,.35);transition:all var(--t) var(--ease)}
.btn-gold:hover{background:var(--gold-dk);transform:translateY(-2px)}

/* HERO */
.about-hero{background:var(--teal-900);position:relative;overflow:hidden;padding:6rem 0 5.5rem}
.about-hero::before{content:'';position:absolute;inset:0;background:radial-gradient(ellipse 65% 70% at 65% 40%,rgba(18,138,105,.32) 0%,transparent 65%),radial-gradient(ellipse 40% 55% at 5% 75%,rgba(11,77,59,.45) 0%,transparent 60%);pointer-events:none}
.about-hero::after{content:'';position:absolute;inset:0;background-image:linear-gradient(rgba(255,255,255,.025) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.025) 1px,transparent 1px);background-size:52px 52px;pointer-events:none}
.about-hero-inner{position:relative;z-index:1;display:grid;grid-template-columns:1fr 1fr;gap:5rem;align-items:center}
.hero-badge{display:inline-flex;align-items:center;gap:8px;font-size:.7rem;font-weight:600;letter-spacing:.14em;text-transform:uppercase;color:var(--teal-400);background:rgba(29,184,133,.12);border:1px solid rgba(29,184,133,.25);padding:5px 14px;border-radius:99px;margin-bottom:1.5rem}
.hero-badge-dot{width:5px;height:5px;border-radius:50%;background:var(--teal-400);animation:pulse 2s ease-in-out infinite}
@keyframes pulse{0%,100%{opacity:1;transform:scale(1)}50%{opacity:.4;transform:scale(.75)}}
.about-hero-title{font-family:'Fraunces',serif;font-size:clamp(2.2rem,4.5vw,3.4rem);font-weight:700;line-height:1.08;letter-spacing:-.03em;color:#fff;margin-bottom:1.2rem}
.about-hero-title em{font-style:italic;color:var(--teal-400)}
.about-hero-body{font-size:1.02rem;color:rgba(255,255,255,.68);line-height:1.78;margin-bottom:2rem;font-weight:300;max-width:480px}
.hero-actions{display:flex;gap:.9rem;flex-wrap:wrap}

/* HERO RIGHT — stat stack */
.hero-stat-stack{display:flex;flex-direction:column;gap:1rem}
.hss-card{background:rgba(255,255,255,.07);backdrop-filter:blur(16px);border:1px solid rgba(255,255,255,.12);border-radius:var(--r-lg);padding:1.4rem 1.6rem;transition:all var(--t) var(--ease)}
.hss-card:hover{background:rgba(255,255,255,.11);border-color:rgba(29,184,133,.3)}
.hss-num{font-family:'Fraunces',serif;font-size:2.4rem;font-weight:700;color:#fff;line-height:1;margin-bottom:4px}
.hss-num span{color:var(--teal-400)}
.hss-label{font-size:.83rem;color:rgba(255,255,255,.55);line-height:1.5}

/* ORIGIN STORY */
.origin-section{background:var(--white);border-top:1px solid var(--grey-100)}
.origin-grid{display:grid;grid-template-columns:1fr 1fr;gap:5rem;align-items:start}
.origin-prose p{font-size:.97rem;color:var(--grey-600);line-height:1.82;margin-bottom:1.2rem}
.origin-prose p:last-child{margin-bottom:0}
.origin-prose strong{color:var(--ink);font-weight:600}
.pull-quote{background:var(--teal-800);border-radius:var(--r-lg);padding:2rem 2.2rem;margin-top:2rem;position:relative;overflow:hidden}
.pull-quote::before{content:'\201C';position:absolute;top:-.5rem;left:1.2rem;font-family:'Fraunces',serif;font-size:6rem;color:rgba(255,255,255,.08);line-height:1;pointer-events:none}
.pull-quote-text{font-family:'Fraunces',serif;font-size:1.1rem;font-style:italic;color:#fff;line-height:1.65;margin-bottom:.8rem;position:relative;z-index:1}
.pull-quote-attr{font-size:.8rem;color:rgba(255,255,255,.45)}

/* TIMELINE */
.timeline{display:flex;flex-direction:column;gap:0;position:relative}
.timeline::before{content:'';position:absolute;left:19px;top:24px;bottom:24px;width:2px;background:linear-gradient(to bottom,var(--teal-600),var(--teal-200));border-radius:99px}
.tl-item{display:flex;gap:1.4rem;align-items:flex-start;position:relative;padding-bottom:2rem}
.tl-item:last-child{padding-bottom:0}
.tl-dot{width:40px;height:40px;border-radius:50%;background:var(--white);border:2px solid var(--teal-600);display:flex;align-items:center;justify-content:center;flex-shrink:0;font-family:'Fraunces',serif;font-weight:700;font-size:.78rem;color:var(--teal-700);box-shadow:0 0 0 4px var(--teal-50);z-index:1}
.tl-content{padding-top:.5rem}
.tl-period{font-size:.72rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--teal-600);margin-bottom:.3rem}
.tl-title{font-family:'Fraunces',serif;font-weight:600;font-size:.98rem;color:var(--ink);margin-bottom:.35rem}
.tl-body{font-size:.86rem;color:var(--grey-600);line-height:1.65}

/* MISSION & VALUES */
.values-section{background:var(--off);border-top:1px solid var(--grey-100)}
.values-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:1.25rem;margin-top:3.5rem}
.value-card{background:var(--white);border:1px solid var(--grey-200);border-radius:var(--r-lg);padding:2rem;transition:all .25s var(--ease);position:relative;overflow:hidden}
.value-card::after{content:'';position:absolute;bottom:0;left:0;right:0;height:3px;background:linear-gradient(90deg,var(--teal-600),var(--teal-400));transform:scaleX(0);transform-origin:left;transition:transform .25s var(--ease)}
.value-card:hover{transform:translateY(-4px);box-shadow:var(--shadow-lg)}
.value-card:hover::after{transform:scaleX(1)}
.value-icon{font-size:2rem;margin-bottom:1.2rem}
.value-title{font-family:'Fraunces',serif;font-weight:700;font-size:1.05rem;color:var(--ink);margin-bottom:.6rem}
.value-body{font-size:.875rem;color:var(--grey-600);line-height:1.7}

/* TEAM */
.team-section{background:var(--white);border-top:1px solid var(--grey-100)}
.team-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:1.5rem;margin-top:3.5rem}
.team-card{background:var(--off);border:1px solid var(--grey-100);border-radius:var(--r-lg);padding:2rem;text-align:center;transition:all var(--t) var(--ease)}
.team-card:hover{box-shadow:var(--shadow-md);border-color:var(--grey-200);transform:translateY(-3px)}
.team-avatar{width:72px;height:72px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-family:'Fraunces',serif;font-weight:700;font-size:1.4rem;color:#fff;margin:0 auto 1.2rem;box-shadow:var(--shadow-md)}
.team-name{font-family:'Fraunces',serif;font-weight:700;font-size:1.05rem;color:var(--ink);margin-bottom:.3rem}
.team-role{font-size:.8rem;font-weight:600;color:var(--teal-600);text-transform:uppercase;letter-spacing:.06em;margin-bottom:.8rem}
.team-bio{font-size:.855rem;color:var(--grey-600);line-height:1.65}

/* ALIGNMENT SECTION */
.alignment-section{background:var(--teal-900);position:relative;overflow:hidden;padding:6rem 0}
.alignment-section::before{content:'';position:absolute;inset:0;background:radial-gradient(ellipse 60% 70% at 75% 50%,rgba(18,138,105,.3) 0%,transparent 65%),radial-gradient(ellipse 40% 55% at 5% 30%,rgba(11,77,59,.5) 0%,transparent 55%);pointer-events:none}
.alignment-inner{position:relative;z-index:1;display:grid;grid-template-columns:1fr 1fr;gap:5rem;align-items:center}
.alignment-eyebrow{font-size:.7rem;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:var(--gold);margin-bottom:.8rem}
.alignment-title{font-family:'Fraunces',serif;font-size:clamp(1.9rem,3.5vw,2.7rem);font-weight:700;color:#fff;line-height:1.1;letter-spacing:-.025em;margin-bottom:1rem}
.alignment-title em{font-style:italic;color:var(--gold)}
.alignment-body{font-size:.97rem;color:rgba(255,255,255,.65);line-height:1.78;margin-bottom:2rem;font-weight:300}
.alignment-pillars{display:flex;flex-direction:column;gap:.85rem}
.alignment-pillar{display:flex;align-items:flex-start;gap:1rem;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);border-radius:var(--r-md);padding:1.1rem 1.3rem;transition:all var(--t) var(--ease)}
.alignment-pillar:hover{background:rgba(255,255,255,.1);border-color:rgba(29,184,133,.3)}
.pillar-icon{font-size:1.3rem;flex-shrink:0;margin-top:1px}
.pillar-title{font-family:'Fraunces',serif;font-weight:600;font-size:.95rem;color:#fff;margin-bottom:3px}
.pillar-body{font-size:.82rem;color:rgba(255,255,255,.55);line-height:1.55}

/* FINAL CTA */
.finalcta-section{background:var(--off);border-top:1px solid var(--grey-100);padding:6rem 0}
.finalcta-card{background:var(--teal-800);border-radius:var(--r-xl);padding:4rem;display:grid;grid-template-columns:1fr auto;gap:3rem;align-items:center;position:relative;overflow:hidden}
.finalcta-card::before{content:'';position:absolute;top:-40%;right:-5%;width:400px;height:400px;border-radius:50%;background:radial-gradient(circle,rgba(29,184,133,.15),transparent 70%);pointer-events:none}
.finalcta-eyebrow{font-size:.7rem;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:var(--teal-400);margin-bottom:.8rem}
.finalcta-title{font-family:'Fraunces',serif;font-size:clamp(1.7rem,3vw,2.4rem);font-weight:700;color:#fff;line-height:1.12;margin-bottom:.8rem;letter-spacing:-.02em}
.finalcta-body{font-size:.95rem;color:rgba(255,255,255,.65);line-height:1.72;font-weight:300}
.finalcta-actions{display:flex;flex-direction:column;gap:.75rem;flex-shrink:0}

/* FOOTER */
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

/* RESPONSIVE */
@media(max-width:1024px){
  .about-hero-inner{grid-template-columns:1fr;gap:3rem}
  .hero-stat-stack{flex-direction:row;flex-wrap:wrap}
  .hss-card{flex:1;min-width:140px}
  .origin-grid{grid-template-columns:1fr;gap:3rem}
  .values-grid{grid-template-columns:1fr 1fr}
  .team-grid{grid-template-columns:1fr 1fr}
  .alignment-inner{grid-template-columns:1fr;gap:3rem}
  .finalcta-card{grid-template-columns:1fr;gap:2rem;padding:2.5rem}
  .footer-top{grid-template-columns:1fr 1fr;gap:2rem}
}
@media(max-width:768px){
  .nav{padding:0 1.25rem}
  .nav-links a:not(.nav-cta){display:none}
  .section{padding:4rem 0}
  .container{padding:0 1.25rem}
  .values-grid{grid-template-columns:1fr}
  .team-grid{grid-template-columns:1fr}
  .footer-top{grid-template-columns:1fr}
  .finalcta-actions{flex-direction:row;flex-wrap:wrap}
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
    <a href="/internhub/about.php" class="active">About</a>
    <?php if(isset($_SESSION['user_id'])): ?>
      <a href="/internhub/auth/dashboard.php"><?php echo htmlspecialchars(explode(' ',$_SESSION['user_name'])[0]); ?> ▾</a>
      <a href="/internhub/auth/actions/logout.php" class="nav-cta">Log Out</a>
    <?php else: ?>
      <a href="/internhub/auth/login.php">Sign In</a>
      <a href="/internhub/auth/register.php" class="nav-cta">Get Started</a>
    <?php endif; ?>
  </div>
</nav>

<!-- HERO -->
<section class="about-hero">
  <div class="container">
    <div class="about-hero-inner">
      <div>
        <div class="hero-badge">
          <span class="hero-badge-dot"></span>
          Built at UICT · Kampala, Uganda
        </div>
        <h1 class="about-hero-title">
          We are students<br/>
          who were unhappy and exhausted by the broken <em>system.</em>
        </h1>
        <p class="about-hero-body">
          The Digital Internship Portal started as a final-year project. It became
          something more — a platform built by UICT students, for UICT students,
          with a real business model and a real problem to solve.
        </p>
        <div class="hero-actions">
          <a href="/internhub/listings.php" class="btn-gold">Browse Internships</a>
          <a href="/internhub/readiness.php" class="btn-ghost-light">Readiness Program</a>
        </div>
      </div>
      <div class="hero-stat-stack">
        <div class="hss-card">
          <div class="hss-num"><?php echo max($totalListings,0); ?><span>+</span></div>
          <div class="hss-label">Verified internship listings live on the platform</div>
        </div>
        <div class="hss-card">
          <div class="hss-num"><?php echo max($totalCompanies,0); ?><span>+</span></div>
          <div class="hss-label">Ugandan organisations partnered and posting</div>
        </div>
        <div class="hss-card">
          <div class="hss-num"><?php echo max($totalStudents,0); ?><span>+</span></div>
          <div class="hss-label">UICT students registered and actively browsing</div>
        </div>
        <div class="hss-card">
          <div class="hss-num">UGX<span> 0</span></div>
          <div class="hss-label">Cost to students — access to opportunities is always free</div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ORIGIN STORY -->
<section class="section origin-section">
  <div class="container">
    <div class="origin-grid">
      <div>
        <div class="s-eyebrow">The Origin</div>
        <h2 class="s-title">A problem we lived personally.</h2>
        <div class="origin-prose">
          <p>Every semester at UICT Nakawa, hundreds of Computer Science and IT students face the same wall: they need an internship to complete their academic requirements, but there is no organised, reliable way to find one. Notice boards. WhatsApp groups. Asking the lecturer who might know someone. That is the system.</p>
          <p>Meanwhile, companies post internship opportunities on platforms built for the general public — and get flooded with irrelevant applications from people with no connection to the role or the institution. Everyone wastes time. Nobody wins.</p>
          <p>We built the Digital Internship Portal because <strong>we are those students</strong>. We know exactly what it feels like to watch an opportunity pass because you didn't hear about it in time. We know what it's like to apply and hear nothing. We decided to stop complaining and build the infrastructure that should have existed already.</p>
          <p>What started as a diploma project became a startup. The judges at UICT will not see a school submission on Friday. They will see a working platform, a defensible business model, and a team that built something real under real constraints.</p>
        </div>
        <div class="pull-quote">
          <p class="pull-quote-text">"BrighterMonday is a marketplace. We are a pipeline. The difference is we don't just post opportunities — we produce the people ready to fill them."</p>
          <p class="pull-quote-attr">— The founding team, Digital Internship Portal</p>
        </div>
      </div>
      <div>
        <div class="s-eyebrow" style="margin-bottom:1.5rem">How We Got Here</div>
        <div class="timeline">
          <?php
          $tl=[
            ['S1','Semester 1 · 2024','The Problem Identified','Running a survey of 60+ UICT students revealed that 68% had difficulty finding internships through official channels. The idea was born.'],
            ['S2','Semester 2 · 2024','The Build Begins','Development started on a PHP/MySQL stack running locally on XAMPP. Core features: student registration, company listing submission, admin review panel.'],
            ['S3','Early 2025','The Business Model','Realising a platform with no revenue model is a tool, not a business. The Readiness Program concept was developed as the primary monetisation and core differentiator.'],
            ['S4','May 2025','Investor Readiness','The platform was redesigned for professional presentation. Revenue projections, NDP IV alignment, and competitive positioning formalised. Pilot phase launched.'],
            ['S5','Next · 2025–26','Scale to Uganda','Following pilot validation at UICT, the model replicates to Makerere, Kyambogo, and other Ugandan institutions. Each gets its own admin, domain restriction, and partner network.'],
          ];
          foreach($tl as [$dot,$period,$title,$body]):
          ?>
          <div class="tl-item">
            <div class="tl-dot"><?php echo $dot; ?></div>
            <div class="tl-content">
              <div class="tl-period"><?php echo $period; ?></div>
              <div class="tl-title"><?php echo $title; ?></div>
              <div class="tl-body"><?php echo $body; ?></div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- MISSION & VALUES -->
<section class="section values-section">
  <div class="container">
    <div style="text-align:center">
      <div class="s-eyebrow" style="display:inline-block">What We Stand For</div>
      <h2 class="s-title">Mission, values, and the way we work.</h2>
      <p class="s-sub" style="margin:0 auto 0">Everything we build comes back to one thing: giving every UICT student a fair shot.</p>
    </div>
    <div class="values-grid">
      <?php
      $values=[
        ['🎯','Students first, always','Students never pay for access. Every design decision on this platform starts with: does this make the student experience better? Monetisation lives entirely on the company side.'],
        ['✅','Quality over volume','We would rather have 10 verified, admin-reviewed listings than 1,000 unverified ones. Every company on this platform has been confirmed as legitimate before their listing goes live.'],
        ['🔒','Trust as infrastructure','Students trust us with their academic futures. Companies trust us with their hiring pipeline. That trust is not taken lightly. It is earned through process, transparency, and accountability.'],
        ['📈','Readiness, not just access','Finding the listing is only half the problem. We invest equally in preparing students to succeed once they walk through the door. The Readiness Program exists because access without preparation is not enough.'],
        ['🌍','Built for Uganda','This platform is not a copy of a Western product adapted for Uganda. It was designed here, for here — Mobile Money payments, UICT email verification, Ugandan company structures, and Kampala-based operations.'],
        ['🚀','Pilot first, scale second','We start at UICT. We prove the model. Then we expand. Controlled growth means we never sacrifice quality for growth metrics. Depth before breadth, always.'],
      ];
      foreach($values as [$icon,$title,$body]):
      ?>
      <div class="value-card">
        <div class="value-icon"><?php echo $icon; ?></div>
        <div class="value-title"><?php echo $title; ?></div>
        <div class="value-body"><?php echo $body; ?></div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- TEAM -->
<section class="section team-section">
  <div class="container">
    <div style="text-align:center">
      <div class="s-eyebrow" style="display:inline-block">The Team</div>
      <h2 class="s-title">Built by students who mean it.</h2>
      <p class="s-sub" style="margin:0 auto">Computer Science Diploma candidates, UICT Nakawa, Class of 2024/2025. We built this while studying. That constraint informed everything.</p>
    </div>
    <div class="team-grid">
      <?php
      $team=[
        ['OB','#0B4D3B','Otuura Brian Oneka','Lead Developer & Product','Built the full-stack platform — PHP backend, MySQL schema, admin panel, and all frontend pages. Responsible for architecture decisions, database design, and deployment.'],
        ['TM','#1B4F72','Team Member','Business & Research','Conducted the student survey that validated the problem. Developed the revenue model, competitive positioning, and NDP IV alignment framework. Leads company outreach.'],
        ['TM','#5B2333','Team Member','Design & Presentation','Responsible for visual design direction, pitch deck preparation, and stakeholder communication. Ensures the product looks and feels like a professional platform, not a school project.'],
      ];
      foreach($team as [$init,$col,$name,$role,$bio]):
      ?>
      <div class="team-card">
        <div class="team-avatar" style="background:<?php echo $col; ?>"><?php echo $init; ?></div>
        <div class="team-name"><?php echo $name; ?></div>
        <div class="team-role"><?php echo $role; ?></div>
        <div class="team-bio"><?php echo $bio; ?></div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- NDP IV ALIGNMENT -->
<section class="alignment-section">
  <div class="container">
    <div class="alignment-inner">
      <div>
        <div class="alignment-eyebrow">🇺🇬 National Alignment</div>
        <h2 class="alignment-title">
          We are not just<br/>building a platform.<br/>
          We are building <em>Uganda's workforce.</em>
        </h2>
        <p class="alignment-body">
          Uganda's National Development Plan IV and Vision 2040 both call for a skilled,
          adaptable, digitally literate youth workforce. The Digital Internship Portal
          directly addresses four of those national priorities — not as a side effect,
          but as a core design decision.
        </p>
        <a href="/internhub/readiness.php" class="btn-gold">See the Readiness Program →</a>
      </div>
      <div class="alignment-pillars">
        <?php
        $pillars=[
          ['📚','Human Capital Development','The Readiness Program directly builds student capacity — CV writing, professional conduct, interview skills — before they enter the workforce.'],
          ['💼','Youth Employment','Structured internship pathways reduce the gap between academic completion and first employment, cutting the time young Ugandans spend unemployed after graduation.'],
          ['💻','Digital Transformation','We replace manual, paper-based internship tracking with a live digital platform — exactly the kind of institutional digitalisation NDP IV calls for.'],
          ['🏗️','Private Sector Growth','Companies are paying partners, not grant recipients. This is a sustainable, market-driven model — private sector led, with government alignment as the context.'],
        ];
        foreach($pillars as [$icon,$title,$body]):
        ?>
        <div class="alignment-pillar">
          <div class="pillar-icon"><?php echo $icon; ?></div>
          <div>
            <div class="pillar-title"><?php echo $title; ?></div>
            <div class="pillar-body"><?php echo $body; ?></div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>

<!-- FINAL CTA -->
<section class="finalcta-section">
  <div class="container">
    <div class="finalcta-card">
      <div>
        <div class="finalcta-eyebrow">Join the Platform</div>
        <h2 class="finalcta-title">This is early stage. That is the point.</h2>
        <p class="finalcta-body">
          We are not pretending to be bigger than we are. We are in pilot phase at UICT,
          with a working platform, a validated problem, and a clear path to revenue.
          The best time to be part of something is before everyone else knows about it.
        </p>
      </div>
      <div class="finalcta-actions">
        <a href="/internhub/auth/register.php?type=student" class="btn-gold">Join as Student</a>
        <a href="/internhub/partners.php" class="btn-primary">Partner with Us</a>
        <a href="/internhub/contact.php" class="btn-outline" style="border-color:rgba(255,255,255,.3);color:#fff">Contact the Team</a>
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