<?php
$activePage = 'readiness';
$pageTitle  = 'Internship Readiness Program';
require_once 'includes/header.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Internship Readiness Program — Digital Internship Portal</title>
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
  --white:#FFFFFF;--off:#F8FAF9;--grey-100:#EEF1EF;
  --grey-200:#D8DFDB;--grey-400:#8A9E96;--grey-600:#4A5E56;
  --ink:#0D1F19;
  --shadow-sm:0 1px 4px rgba(6,46,34,.07);
  --shadow-md:0 4px 20px rgba(6,46,34,.10);
  --shadow-lg:0 12px 48px rgba(6,46,34,.13);
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
.nav-brand{display:flex;align-items:center;gap:10px;font-family:'Fraunces',serif;font-size:1.1rem;font-weight:700;color:var(--teal-800)}
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

/* HERO */
.page-hero{background:var(--teal-900);position:relative;overflow:hidden;padding:6rem 0 5rem}
.page-hero::before{content:'';position:absolute;inset:0;background:radial-gradient(ellipse 70% 60% at 60% 40%,rgba(18,138,105,.3) 0%,transparent 70%),radial-gradient(ellipse 40% 50% at 10% 80%,rgba(11,77,59,.4) 0%,transparent 60%);pointer-events:none}
.page-hero::after{content:'';position:absolute;inset:0;background-image:linear-gradient(rgba(255,255,255,.025) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.025) 1px,transparent 1px);background-size:52px 52px;pointer-events:none}
.page-hero-inner{position:relative;z-index:1;max-width:760px}
.page-hero-badge{display:inline-flex;align-items:center;gap:8px;font-size:.72rem;font-weight:600;letter-spacing:.14em;text-transform:uppercase;color:var(--gold);background:rgba(212,160,23,.12);border:1px solid rgba(212,160,23,.25);padding:5px 14px;border-radius:99px;margin-bottom:1.5rem}
.page-hero-title{font-family:'Fraunces',serif;font-size:clamp(2.4rem,5vw,3.6rem);font-weight:700;line-height:1.08;letter-spacing:-.03em;color:#fff;margin-bottom:1.2rem}
.page-hero-title em{font-style:italic;color:var(--gold)}
.page-hero-body{font-size:1.05rem;color:rgba(255,255,255,.68);line-height:1.78;max-width:580px;margin-bottom:2rem;font-weight:300}
.hero-actions{display:flex;gap:.9rem;flex-wrap:wrap}
.btn-gold{display:inline-flex;align-items:center;gap:8px;background:var(--gold);color:var(--ink);font-family:'Plus Jakarta Sans',sans-serif;font-weight:700;font-size:.9rem;padding:13px 28px;border-radius:var(--r-sm);border:none;cursor:pointer;box-shadow:0 4px 16px rgba(212,160,23,.35);transition:all var(--t) var(--ease)}
.btn-gold:hover{background:var(--gold-dk);transform:translateY(-2px)}
.btn-ghost-light{display:inline-flex;align-items:center;gap:8px;background:rgba(255,255,255,.1);color:#fff;font-family:'Plus Jakarta Sans',sans-serif;font-weight:500;font-size:.9rem;padding:13px 28px;border-radius:var(--r-sm);border:1px solid rgba(255,255,255,.2);transition:all var(--t) var(--ease)}
.btn-ghost-light:hover{background:rgba(255,255,255,.18)}

/* STATS BAND */
.stats-band{background:var(--white);border-bottom:1px solid var(--grey-100);padding:2.2rem 0}
.stats-row{display:grid;grid-template-columns:repeat(4,1fr);gap:0}
.stat-block{text-align:center;padding:1rem 1.5rem;border-right:1px solid var(--grey-100)}
.stat-block:last-child{border-right:none}
.stat-num{font-family:'Fraunces',serif;font-size:2.2rem;font-weight:700;color:var(--teal-800);line-height:1;margin-bottom:4px}
.stat-label{font-size:.8rem;color:var(--grey-400)}

/* WHY IT EXISTS */
.why-section{background:var(--off);border-top:1px solid var(--grey-100)}
.why-grid{display:grid;grid-template-columns:1fr 1fr;gap:4rem;align-items:center;margin-top:3rem}
.why-text p{font-size:.95rem;color:var(--grey-600);line-height:1.78;margin-bottom:1rem}
.why-quote{background:var(--teal-800);border-radius:var(--r-lg);padding:2rem;margin-top:1.5rem}
.why-quote-text{font-family:'Fraunces',serif;font-size:1.15rem;font-style:italic;color:#fff;line-height:1.6;margin-bottom:1rem}
.why-quote-attr{font-size:.8rem;color:rgba(255,255,255,.5)}
.why-cards{display:flex;flex-direction:column;gap:1rem}
.why-card{background:var(--white);border:1px solid var(--grey-200);border-radius:var(--r-md);padding:1.3rem;display:flex;gap:1.2rem;align-items:flex-start;transition:all var(--t) var(--ease)}
.why-card:hover{border-color:var(--teal-200);box-shadow:var(--shadow-md);transform:translateX(4px)}
.why-card-icon{width:42px;height:42px;border-radius:var(--r-sm);background:var(--teal-50);border:1px solid var(--teal-100);display:flex;align-items:center;justify-content:center;font-size:1.2rem;flex-shrink:0}
.why-card-title{font-family:'Fraunces',serif;font-weight:600;font-size:.95rem;color:var(--ink);margin-bottom:.3rem}
.why-card-body{font-size:.85rem;color:var(--grey-600);line-height:1.6}

/* CURRICULUM */
.curriculum-section{background:var(--white);border-top:1px solid var(--grey-100)}
.curriculum-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:1.25rem;margin-top:3.5rem}
.module-card{background:var(--off);border:1px solid var(--grey-100);border-radius:var(--r-lg);overflow:hidden;transition:all .25s var(--ease)}
.module-card:hover{box-shadow:var(--shadow-lg);border-color:var(--grey-200);transform:translateY(-3px)}
.module-card-header{padding:1.5rem 1.8rem 1.2rem;border-bottom:1px solid var(--grey-100);display:flex;align-items:flex-start;gap:1rem}
.module-num{font-family:'Fraunces',serif;font-size:2.5rem;font-weight:700;color:var(--teal-100);line-height:1;flex-shrink:0}
.module-icon-wrap{width:44px;height:44px;border-radius:var(--r-sm);background:var(--teal-50);border:1px solid var(--teal-100);display:flex;align-items:center;justify-content:center;font-size:1.3rem;flex-shrink:0}
.module-title{font-family:'Fraunces',serif;font-weight:700;font-size:1.1rem;color:var(--ink);margin-bottom:.3rem}
.module-duration{font-size:.78rem;color:var(--teal-600);font-weight:600}
.module-card-body{padding:1.2rem 1.8rem 1.5rem}
.module-desc{font-size:.88rem;color:var(--grey-600);line-height:1.65;margin-bottom:1rem}
.module-topics{display:flex;flex-wrap:wrap;gap:.5rem}
.module-topic{font-size:.75rem;font-weight:500;background:var(--white);border:1px solid var(--grey-200);color:var(--grey-600);padding:3px 10px;border-radius:99px}

/* PRICING */
.pricing-section{background:var(--teal-900);position:relative;overflow:hidden;padding:6rem 0}
.pricing-section::before{content:'';position:absolute;inset:0;background:radial-gradient(ellipse 60% 70% at 70% 50%,rgba(18,138,105,.28) 0%,transparent 65%),radial-gradient(ellipse 40% 50% at 5% 30%,rgba(11,77,59,.5) 0%,transparent 55%);pointer-events:none}
.pricing-inner{position:relative;z-index:1}
.pricing-header{text-align:center;margin-bottom:3.5rem}
.pricing-header .s-eyebrow{color:var(--teal-400)}
.pricing-header .s-title{color:#fff}
.pricing-header .s-sub{color:rgba(255,255,255,.6);margin:0 auto}
.pricing-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:1.5rem;align-items:start}
.price-card{background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);border-radius:var(--r-xl);padding:2.2rem;transition:all var(--t) var(--ease)}
.price-card:hover{background:rgba(255,255,255,.09);transform:translateY(-4px)}
.price-card-featured{background:var(--white);border-color:var(--white)}
.price-card-featured:hover{background:var(--white)}
.price-card-badge{display:inline-block;font-size:.68rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;background:var(--gold);color:var(--ink);padding:3px 10px;border-radius:99px;margin-bottom:1.2rem}
.price-card-name{font-family:'Fraunces',serif;font-weight:700;font-size:1.15rem;color:#fff;margin-bottom:.4rem}
.price-card-featured .price-card-name{color:var(--ink)}
.price-card-desc{font-size:.85rem;color:rgba(255,255,255,.55);margin-bottom:1.5rem;line-height:1.6}
.price-card-featured .price-card-desc{color:var(--grey-600)}
.price-amount{display:flex;align-items:flex-end;gap:4px;margin-bottom:.3rem}
.price-currency{font-size:.9rem;color:rgba(255,255,255,.5);margin-bottom:.3rem}
.price-card-featured .price-currency{color:var(--grey-400)}
.price-num{font-family:'Fraunces',serif;font-size:2.8rem;font-weight:700;color:#fff;line-height:1}
.price-card-featured .price-num{color:var(--ink)}
.price-period{font-size:.82rem;color:rgba(255,255,255,.4);margin-bottom:1.5rem}
.price-card-featured .price-period{color:var(--grey-400)}
.price-divider{height:1px;background:rgba(255,255,255,.1);margin-bottom:1.5rem}
.price-card-featured .price-divider{background:var(--grey-100)}
.price-features{list-style:none;display:flex;flex-direction:column;gap:.7rem;margin-bottom:2rem}
.price-features li{font-size:.875rem;color:rgba(255,255,255,.75);display:flex;gap:9px;align-items:flex-start}
.price-card-featured .price-features li{color:var(--grey-600)}
.price-features li::before{content:'✓';color:var(--teal-400);font-weight:700;flex-shrink:0;margin-top:1px}
.price-card-featured .price-features li::before{color:var(--teal-700)}
.price-features li.disabled{opacity:.4}
.price-features li.disabled::before{content:'–';color:rgba(255,255,255,.3)}
.price-card-featured .price-features li.disabled::before{color:var(--grey-300)}
.btn-price-primary{width:100%;padding:12px;background:var(--gold);color:var(--ink);font-family:'Plus Jakarta Sans',sans-serif;font-weight:700;font-size:.9rem;border:none;border-radius:var(--r-sm);cursor:pointer;box-shadow:0 3px 12px rgba(212,160,23,.3);transition:all var(--t) var(--ease);text-align:center;display:block}
.btn-price-primary:hover{background:var(--gold-dk);transform:translateY(-1px)}
.btn-price-ghost{width:100%;padding:12px;background:transparent;color:#fff;font-family:'Plus Jakarta Sans',sans-serif;font-weight:600;font-size:.9rem;border:1px solid rgba(255,255,255,.25);border-radius:var(--r-sm);cursor:pointer;transition:all var(--t) var(--ease);text-align:center;display:block}
.btn-price-ghost:hover{background:rgba(255,255,255,.1)}
.btn-price-outline{width:100%;padding:12px;background:transparent;color:var(--teal-800);font-family:'Plus Jakarta Sans',sans-serif;font-weight:600;font-size:.9rem;border:1.5px solid var(--teal-800);border-radius:var(--r-sm);cursor:pointer;transition:all var(--t) var(--ease);text-align:center;display:block}
.btn-price-outline:hover{background:var(--teal-50)}

/* OUTCOME */
.outcome-section{background:var(--off);border-top:1px solid var(--grey-100)}
.outcome-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:1.25rem;margin-top:3.5rem}
.outcome-card{background:var(--white);border:1px solid var(--grey-200);border-radius:var(--r-lg);padding:2rem;text-align:center;transition:all .25s var(--ease);position:relative;overflow:hidden}
.outcome-card::after{content:'';position:absolute;bottom:0;left:0;right:0;height:3px;background:linear-gradient(90deg,var(--teal-600),var(--teal-400));transform:scaleX(0);transform-origin:left;transition:transform .25s var(--ease)}
.outcome-card:hover{transform:translateY(-4px);box-shadow:var(--shadow-lg)}
.outcome-card:hover::after{transform:scaleX(1)}
.outcome-icon{font-size:2.2rem;margin-bottom:1rem}
.outcome-num{font-family:'Fraunces',serif;font-size:2.4rem;font-weight:700;color:var(--teal-700);margin-bottom:.3rem}
.outcome-title{font-family:'Fraunces',serif;font-weight:600;font-size:1rem;color:var(--ink);margin-bottom:.5rem}
.outcome-body{font-size:.85rem;color:var(--grey-600);line-height:1.6}

/* ENROLL CTA */
.enroll-section{background:var(--white);border-top:1px solid var(--grey-100);padding:6rem 0}
.enroll-card{background:var(--teal-800);border-radius:var(--r-xl);padding:4rem;display:grid;grid-template-columns:1fr 1fr;gap:4rem;align-items:center;position:relative;overflow:hidden}
.enroll-card::before{content:'';position:absolute;top:-30%;right:-5%;width:350px;height:350px;border-radius:50%;background:radial-gradient(circle,rgba(29,184,133,.15),transparent 70%);pointer-events:none}
.enroll-eyebrow{font-size:.7rem;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:var(--teal-400);margin-bottom:.8rem}
.enroll-title{font-family:'Fraunces',serif;font-size:clamp(1.7rem,3vw,2.4rem);font-weight:700;color:#fff;line-height:1.12;letter-spacing:-.02em;margin-bottom:.8rem}
.enroll-body{font-size:.95rem;color:rgba(255,255,255,.65);line-height:1.72;margin-bottom:1.8rem;font-weight:300}
.enroll-actions{display:flex;gap:.9rem;flex-wrap:wrap}
.enroll-info{display:flex;flex-direction:column;gap:1rem}
.enroll-info-item{display:flex;align-items:center;gap:12px;background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.1);border-radius:var(--r-md);padding:1.1rem 1.3rem}
.enroll-info-icon{font-size:1.3rem;flex-shrink:0}
.enroll-info-label{font-size:.75rem;font-weight:600;color:rgba(255,255,255,.45);text-transform:uppercase;letter-spacing:.06em;margin-bottom:2px}
.enroll-info-value{font-family:'Fraunces',serif;font-weight:600;font-size:.95rem;color:#fff}

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

@media(max-width:1024px){
  .pricing-grid{grid-template-columns:1fr 1fr}
  .curriculum-grid{grid-template-columns:1fr}
  .why-grid{grid-template-columns:1fr;gap:2rem}
  .footer-top{grid-template-columns:1fr 1fr;gap:2rem}
  .outcome-grid{grid-template-columns:1fr 1fr}
  .enroll-card{grid-template-columns:1fr;gap:2.5rem;padding:2.5rem}
}
@media(max-width:768px){
  .nav{padding:0 1.25rem}
  .nav-links a:not(.nav-cta){display:none}
  .section{padding:4rem 0}
  .container{padding:0 1.25rem}
  .pricing-grid{grid-template-columns:1fr}
  .stats-row{grid-template-columns:1fr 1fr}
  .outcome-grid{grid-template-columns:1fr}
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
    <a href="/internhub/readiness.php" class="active">Readiness Program</a>
    <a href="/internhub/partners.php">For Companies</a>
    <a href="/internhub/about.php">About</a>
    <?php if(isset($_SESSION['user_id'])): ?>
      <a href="/internhub/auth/dashboard.php"><?php echo htmlspecialchars(explode(' ',$_SESSION['user_name'])[0]); ?> ▾</a>
      <a href="/internhub/auth/actions/logout.php" class="nav-cta">Log Out</a>
    <?php else: ?>
      <a href="/internhub/auth/login.php">Sign In</a>
      <a href="/internhub/auth/register.php" class="nav-cta">Get Started</a>
    <?php endif; ?>
  </div>
</nav>

<!-- PAGE HERO -->
<section class="page-hero">
  <div class="container">
    <div class="page-hero-inner">
      <div class="page-hero-badge">🎓 Internship Readiness Program</div>
      <h1 class="page-hero-title">
        Finding the internship<br/>
        is only <em>half</em> the work.
      </h1>
      <p class="page-hero-body">
        The Internship Readiness Program prepares UICT Computer Science students
        for the realities of professional environments — before they walk through
        the door. CV writing, interview skills, workplace professionalism,
        and technical refreshers. All in one structured programme.
      </p>
      <div class="hero-actions">
        <a href="/internhub/readiness-enroll.php" class="btn-gold" style="background:#16A34A">
          🎓 Enroll Now → Get Certified
          <svg width="14" height="14" viewBox="0 0 14 14" fill="none"><path d="M3 7h8M8 3.5L11.5 7 8 10.5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </a>
        <a href="#curriculum" class="btn-ghost-light">See the Curriculum</a>
      </div>
    </div>
  </div>
</section>

<!-- STATS -->
<section class="stats-band">
  <div class="container">
    <div class="stats-row">
      <div class="stat-block">
        <div class="stat-num">5</div>
        <div class="stat-label">Core Modules</div>
      </div>
      <div class="stat-block">
        <div class="stat-num">3–4</div>
        <div class="stat-label">Weeks Duration</div>
      </div>
      <div class="stat-block">
        <div class="stat-num">UGX 50K</div>
        <div class="stat-label">Maximum Fee</div>
      </div>
      <div class="stat-block">
        <div class="stat-num">✓</div>
        <div class="stat-label">Certificate Included</div>
      </div>
    </div>
  </div>
</section>

<!-- WHY IT EXISTS -->
<section class="section why-section">
  <div class="container">
    <div class="why-grid">
      <div class="why-text">
        <div class="s-eyebrow">Why This Exists</div>
        <h2 class="s-title">The gap nobody talks about</h2>
        <p>Uganda's ICT sector is growing rapidly. Companies are actively looking for young talent. Yet a persistent mismatch exists between what universities produce and what employers expect.</p>
        <p>Students graduate with theoretical knowledge but arrive at internships without a professional CV, without interview experience, and without an understanding of how corporate environments operate. Employers waste weeks onboarding interns who should have arrived ready.</p>
        <p>The Readiness Program closes that gap. It is not a replacement for academic training — it is the bridge between the classroom and the boardroom.</p>
        <div class="why-quote">
          <p class="why-quote-text">"We love taking interns from UICT — they are technically capable. But we spend the first two weeks teaching them things that have nothing to do with the job. Email etiquette. Meeting conduct. How to write a professional message. That time costs us."</p>
          <p class="why-quote-attr">— HR Manager, Kampala-based tech company</p>
        </div>
      </div>
      <div class="why-cards">
        <?php
        $whys=[
          ['📉','68% of students report difficulty','Most UICT students have never written a professional CV or attended a formal interview before their first placement attempt.'],
          ['⏱','2–3 weeks of employer onboarding','On average, companies report spending 2–3 weeks on basic professional orientation before interns can contribute meaningfully.'],
          ['🔄','High early dropout rates','Interns unprepared for professional environments leave placements early — creating disruption for both students and employers.'],
          ['🎯','Aligned with Uganda NDP IV','Uganda\'s National Development Plan IV prioritises employable youth and a productive workforce. The Readiness Program directly addresses this national priority.'],
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

<!-- CURRICULUM -->
<section class="section curriculum-section" id="curriculum">
  <div class="container">
    <div class="s-eyebrow">Curriculum</div>
    <h2 class="s-title">Five modules. Four weeks. One certificate.</h2>
    <p class="s-sub">Every module is designed specifically for UICT Computer Science and IT students entering the Ugandan job market.</p>

    <div class="curriculum-grid">
      <?php
      $modules=[
        ['01','📄','CV & Personal Branding','Week 1 · 3 sessions',
          'Students learn to build a professional CV tailored to Ugandan employers, create a LinkedIn profile, and develop a personal pitch that communicates their skills and value clearly.',
          ['CV structure and formatting','Skills and experience framing','LinkedIn profile creation','Personal elevator pitch','Cover letter writing']],
        ['02','🎤','Interview Preparation','Week 1–2 · 4 sessions',
          'Practical mock interview sessions covering common technical and behavioural questions, body language, salary negotiation basics, and how to follow up professionally after an interview.',
          ['Common interview questions','Behavioural (STAR) technique','Technical Q&A by field','Mock interview practice','Post-interview follow-up']],
        ['03','🏢','Workplace Professionalism','Week 2–3 · 3 sessions',
          'Everything the university does not teach: corporate email etiquette, meeting conduct, managing upward, dealing with feedback, punctuality standards, and navigating different workplace cultures in Uganda.',
          ['Email and communication etiquette','Meeting and presentation conduct','Giving and receiving feedback','Time management in a work context','Professional relationships and boundaries']],
        ['04','💻','Technical Refresher by Field','Week 3 · 2 sessions',
          'Field-specific technical sessions tailored to the student\'s declared area — Web Development, Data & Analytics, Cybersecurity, Networking, or Mobile Development. Designed to close knowledge gaps before placement.',
          ['Web Dev: HTML/CSS/JS/PHP recap','Data: Excel, SQL, basic Python','Cybersecurity: Fundamentals recap','Networking: TCP/IP, Cisco basics','Mobile: React Native / Flutter intro']],
        ['05','🏆','Capstone & Certification','Week 4 · 1 session',
          'Students complete a short professional readiness assessment and present a personal career plan. Successful completers receive a Digital Internship Portal Certificate of Readiness — recognised by all portal partner companies.',
          ['Professional readiness assessment','Personal career plan presentation','Peer feedback session','Certificate of Readiness awarded','Profile marked as Readiness-Certified']],
      ];
      foreach($modules as [$num,$icon,$title,$duration,$desc,$topics]):
      ?>
      <div class="module-card">
        <div class="module-card-header">
          <div class="module-num"><?php echo $num; ?></div>
          <div>
            <div style="display:flex;align-items:center;gap:.7rem;margin-bottom:.4rem">
              <div class="module-icon-wrap"><?php echo $icon; ?></div>
              <div>
                <div class="module-title"><?php echo $title; ?></div>
                <div class="module-duration"><?php echo $duration; ?></div>
              </div>
            </div>
          </div>
        </div>
        <div class="module-card-body">
          <p class="module-desc"><?php echo $desc; ?></p>
          <div class="module-topics">
            <?php foreach($topics as $t): ?>
              <span class="module-topic"><?php echo $t; ?></span>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- PRICING -->
<section class="pricing-section" id="pricing">
  <div class="container pricing-inner">
    <div class="pricing-header">
      <div class="s-eyebrow">Pricing</div>
      <h2 class="s-title">Simple, accessible pricing</h2>
      <p class="s-sub">Designed to be affordable for every UICT student. No hidden fees. Certificate included in all tiers.</p>
    </div>
    <div class="pricing-grid">

      <div class="price-card">
        <div class="price-card-name">Standard</div>
        <div class="price-card-desc">Full access to all five modules and the completion certificate.</div>
        <div class="price-amount">
          <span class="price-currency">UGX</span>
          <span class="price-num">30,000</span>
        </div>
        <div class="price-period">per cohort · one-time payment</div>
        <div class="price-divider"></div>
        <ul class="price-features">
          <li>Access to all 5 modules</li>
          <li>CV template and review</li>
          <li>Mock interview session</li>
          <li>Certificate of Readiness</li>
          <li>Readiness-Certified badge on profile</li>
          <li class="disabled">Priority listing visibility</li>
          <li class="disabled">1-on-1 career coaching session</li>
        </ul>
        <a href="/internhub/readiness-enroll.php" class="btn-price-ghost">Enroll Now</a>
      </div>

      <div class="price-card price-card-featured">
        <div class="price-card-badge">Most Popular</div>
        <div class="price-card-name">Professional</div>
        <div class="price-card-desc">Everything in Standard plus priority visibility and a personal coaching session.</div>
        <div class="price-amount">
          <span class="price-currency" style="color:var(--grey-400)">UGX</span>
          <span class="price-num">50,000</span>
        </div>
        <div class="price-period">per cohort · one-time payment</div>
        <div class="price-divider"></div>
        <ul class="price-features">
          <li>Access to all 5 modules</li>
          <li>CV template, review & personalisation</li>
          <li>Two mock interview sessions</li>
          <li>Certificate of Readiness</li>
          <li>Readiness-Certified badge on profile</li>
          <li>Priority visibility in listings search</li>
          <li>1-on-1 career coaching session (45 min)</li>
        </ul>
        <a href="/internhub/readiness-enroll.php" class="btn-price-primary">Enroll Now — Get Certified</a>
      </div>

      <div class="price-card">
        <div class="price-card-name">Institution</div>
        <div class="price-card-desc">Bulk enrolment for UICT departments or partner institutions. Custom pricing.</div>
        <div class="price-amount">
          <span class="price-num" style="font-size:2rem">Custom</span>
        </div>
        <div class="price-period">per cohort · group rates apply</div>
        <div class="price-divider"></div>
        <ul class="price-features">
          <li>Everything in Professional</li>
          <li>Dedicated cohort scheduling</li>
          <li>Department-level progress reporting</li>
          <li>Co-branded certificate option</li>
          <li>Dedicated facilitator</li>
          <li>Minimum 20 students per cohort</li>
          <li>Annual partnership agreement</li>
        </ul>
        <a href="/internhub/contact.php" class="btn-price-ghost">Contact for Pricing</a>
      </div>

    </div>
  </div>
</section>

<!-- OUTCOMES -->
<section class="section outcome-section">
  <div class="container">
    <div style="text-align:center">
      <div class="s-eyebrow" style="display:inline-block">Expected Outcomes</div>
      <h2 class="s-title">What completing the program means</h2>
    </div>
    <div class="outcome-grid">
      <?php
      $outcomes=[
        ['🎯','Placed faster','Readiness-certified students apply with confidence and are shortlisted more frequently by partner companies.'],
        ['⚡','Perform from day one','Employers report significantly reduced onboarding time for students who have completed the Readiness Program.'],
        ['📜','Recognised certificate','The Certificate of Readiness is recognised by all Digital Internship Portal partner companies as a mark of professional preparation.'],
        ['🔗','Network access','Completers join the DIP Alumni network — connecting with previous cohort members, mentors, and partner company representatives.'],
        ['👁','Profile visibility boost','Professional-tier completers receive priority placement in search results — meaning companies see their profiles first.'],
        ['🚀','Career foundation','The skills covered in the program go beyond internship preparation — they form the foundation of a professional career.'],
      ];
      foreach($outcomes as [$icon,$title,$body]):
      ?>
      <div class="outcome-card">
        <div class="outcome-icon"><?php echo $icon; ?></div>
        <div class="outcome-title"><?php echo $title; ?></div>
        <div class="outcome-body"><?php echo $body; ?></div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ENROLL CTA -->
<section class="enroll-section">
  <div class="container">
    <div class="enroll-card">
      <div>
        <div class="enroll-eyebrow">Ready to Begin?</div>
        <h2 class="enroll-title">Your next cohort starts Q3 2025</h2>
        <p class="enroll-body">Register now to secure your place in the next Readiness cohort. Spaces are limited to ensure every student gets the attention they need.</p>
        <div class="enroll-actions">
          <a href="/internhub/readiness-enroll.php" class="btn-gold">Enroll Now → Get Certified</a>
          <a href="/internhub/contact.php" class="btn-ghost-light">Ask a Question</a>
        </div>
      </div>
      <div class="enroll-info">
        <?php
        $info=[
          ['📅','Next Cohort Begins','Q3 2025 — Register now to secure your place'],
          ['⏱','Programme Duration','3–4 weeks · Part-time · Compatible with studies'],
          ['💳','Payment','UGX 30,000 – 50,000 · Mobile Money accepted'],
          ['📍','Delivery','In-person at UICT Nakawa + online sessions'],
          ['📜','Certificate','Issued within 5 business days of completion'],
        ];
        foreach($info as [$icon,$label,$value]):
        ?>
        <div class="enroll-info-item">
          <div class="enroll-info-icon"><?php echo $icon; ?></div>
          <div>
            <div class="enroll-info-label"><?php echo $label; ?></div>
            <div class="enroll-info-value"><?php echo $value; ?></div>
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