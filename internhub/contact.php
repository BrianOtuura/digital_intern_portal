<?php
$activePage = 'contact';
$pageTitle  = 'Contact Us';
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once 'includes/db.php';
require_once 'includes/header.php';

$sent  = $_GET['sent']  ?? '';
$error = $_GET['error'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Contact — Digital Internship Portal</title>
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
.page-hero{background:var(--teal-900);position:relative;overflow:hidden;padding:5rem 0 4.5rem}
.page-hero::before{content:'';position:absolute;inset:0;background:radial-gradient(ellipse 70% 60% at 60% 40%,rgba(18,138,105,.3) 0%,transparent 70%);pointer-events:none}
.page-hero::after{content:'';position:absolute;inset:0;background-image:linear-gradient(rgba(255,255,255,.025) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.025) 1px,transparent 1px);background-size:52px 52px;pointer-events:none}
.page-hero-inner{position:relative;z-index:1;max-width:700px}
.hero-badge{display:inline-flex;align-items:center;gap:8px;font-size:.7rem;font-weight:600;letter-spacing:.14em;text-transform:uppercase;color:var(--teal-400);background:rgba(29,184,133,.12);border:1px solid rgba(29,184,133,.25);padding:5px 14px;border-radius:99px;margin-bottom:1.5rem}
.page-hero-title{font-family:'Fraunces',serif;font-size:clamp(2.2rem,4.5vw,3.2rem);font-weight:700;line-height:1.08;letter-spacing:-.03em;color:#fff;margin-bottom:1rem}
.page-hero-sub{font-size:1rem;color:rgba(255,255,255,.65);line-height:1.75;font-weight:300}

/* CONTAINER */
.container{max-width:1200px;margin:0 auto;padding:0 2.5rem}

/* MAIN LAYOUT */
.contact-layout{display:grid;grid-template-columns:1fr 400px;gap:2.5rem;padding:4rem 0 6rem;align-items:start}

/* FORM CARD */
.form-card{background:var(--white);border:1px solid var(--grey-100);border-radius:var(--r-xl);overflow:hidden}
.form-card-header{padding:2rem 2.2rem 1.5rem;border-bottom:1px solid var(--grey-100)}
.form-card-title{font-family:'Fraunces',serif;font-weight:700;font-size:1.25rem;color:var(--ink);margin-bottom:.3rem}
.form-card-sub{font-size:.875rem;color:var(--grey-600)}
.form-card-body{padding:2rem 2.2rem}

/* FORM FIELDS */
.field-row{display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1.1rem}
.field{display:flex;flex-direction:column;gap:.4rem;margin-bottom:1.1rem}
.field-label{font-size:.78rem;font-weight:600;color:var(--ink);letter-spacing:.02em}
.field-label span{color:var(--teal-600)}
.field-input,.field-select,.field-textarea{width:100%;font-family:'Plus Jakarta Sans',sans-serif;font-size:.9rem;color:var(--ink);background:var(--off);border:1.5px solid var(--grey-200);border-radius:var(--r-md);padding:11px 14px;outline:none;transition:border-color var(--t) var(--ease),box-shadow var(--t) var(--ease)}
.field-input:focus,.field-select:focus,.field-textarea:focus{border-color:var(--teal-600);box-shadow:0 0 0 3px var(--teal-50);background:var(--white)}
.field-input::placeholder,.field-textarea::placeholder{color:var(--grey-400)}
.field-textarea{resize:vertical;min-height:130px;line-height:1.65}
.field-select{cursor:pointer;appearance:none;background-image:url("data:image/svg+xml,%3Csvg width='12' height='8' viewBox='0 0 12 8' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1 1L6 7L11 1' stroke='%238A9E96' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 14px center;padding-right:36px}
.field-hint{font-size:.75rem;color:var(--grey-400);margin-top:3px}

/* SUBMIT BUTTON */
.btn-submit{width:100%;padding:13px;background:var(--teal-800);color:#fff;font-family:'Plus Jakarta Sans',sans-serif;font-weight:700;font-size:.95rem;border:none;border-radius:var(--r-md);cursor:pointer;box-shadow:0 2px 10px rgba(11,77,59,.25);transition:all var(--t) var(--ease);display:flex;align-items:center;justify-content:center;gap:8px;margin-top:1.5rem}
.btn-submit:hover{background:var(--teal-900);transform:translateY(-1px)}

/* ALERTS */
.alert{border-radius:var(--r-md);padding:1rem 1.2rem;margin-bottom:1.5rem;font-size:.88rem;display:flex;align-items:flex-start;gap:.75rem}
.alert-success{background:#EDF9F5;border:1px solid var(--teal-200);color:var(--teal-700)}
.alert-error{background:#FFF1F1;border:1px solid #FECACA;color:#B91C1C}
.alert-icon{font-size:1.1rem;flex-shrink:0}

/* SIDEBAR */
.contact-sidebar{display:flex;flex-direction:column;gap:1.25rem;position:sticky;top:88px}

/* INFO CARDS */
.info-card{background:var(--white);border:1px solid var(--grey-100);border-radius:var(--r-lg);overflow:hidden}
.info-card-header{padding:1.2rem 1.4rem;border-bottom:1px solid var(--grey-100)}
.info-card-title{font-family:'Fraunces',serif;font-weight:700;font-size:.98rem;color:var(--ink)}
.info-rows{display:flex;flex-direction:column}
.info-row{display:flex;gap:1rem;align-items:flex-start;padding:1.1rem 1.4rem;border-bottom:1px solid var(--grey-100);transition:background var(--t) var(--ease)}
.info-row:last-child{border-bottom:none}
.info-row:hover{background:var(--off)}
.info-icon{font-size:1.2rem;flex-shrink:0;margin-top:1px}
.info-label{font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--grey-400);margin-bottom:3px}
.info-value{font-size:.88rem;color:var(--ink);font-weight:500;line-height:1.5}
.info-value a{color:var(--teal-700);transition:color var(--t) var(--ease)}
.info-value a:hover{color:var(--teal-900)}

/* HOURS CARD */
.hours-card{background:var(--teal-800);border-radius:var(--r-lg);padding:1.6rem;position:relative;overflow:hidden}
.hours-card::before{content:'';position:absolute;top:-20%;right:-10%;width:150px;height:150px;border-radius:50%;background:radial-gradient(circle,rgba(29,184,133,.2),transparent 70%);pointer-events:none}
.hours-eyebrow{font-size:.68rem;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:var(--teal-400);margin-bottom:.5rem}
.hours-title{font-family:'Fraunces',serif;font-weight:700;font-size:1rem;color:#fff;margin-bottom:1rem}
.hours-rows{display:flex;flex-direction:column;gap:.55rem}
.hours-row{display:flex;justify-content:space-between;align-items:center;font-size:.83rem}
.hours-day{color:rgba(255,255,255,.55)}
.hours-time{color:#fff;font-weight:600}
.hours-row.today .hours-day{color:var(--teal-400);font-weight:600}
.hours-row.today .hours-time{color:var(--teal-400)}

/* FAQ */
.faq-section{padding:5rem 0;border-top:1px solid var(--grey-100)}
.faq-header{text-align:center;margin-bottom:3rem}
.s-eyebrow{font-size:.7rem;font-weight:700;letter-spacing:.16em;text-transform:uppercase;color:var(--teal-600);margin-bottom:.5rem;display:inline-block}
.s-title{font-family:'Fraunces',serif;font-size:clamp(1.8rem,3.5vw,2.4rem);font-weight:700;color:var(--ink);line-height:1.12;letter-spacing:-.025em;margin-top:.5rem}
.faq-grid{display:grid;grid-template-columns:1fr 1fr;gap:1.25rem}
.faq-item{background:var(--white);border:1px solid var(--grey-100);border-radius:var(--r-lg);padding:1.6rem;transition:all var(--t) var(--ease)}
.faq-item:hover{border-color:var(--grey-200);box-shadow:var(--shadow-md)}
.faq-q{font-family:'Fraunces',serif;font-weight:700;font-size:.98rem;color:var(--ink);margin-bottom:.6rem;line-height:1.3}
.faq-a{font-size:.875rem;color:var(--grey-600);line-height:1.72}

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
  .contact-layout{grid-template-columns:1fr;gap:2rem}
  .contact-sidebar{position:static}
  .faq-grid{grid-template-columns:1fr}
  .footer-top{grid-template-columns:1fr 1fr;gap:2rem}
}
@media(max-width:768px){
  .nav{padding:0 1.25rem}
  .nav-links a:not(.nav-cta){display:none}
  .container{padding:0 1.25rem}
  .contact-layout{padding:2.5rem 0 4rem}
  .field-row{grid-template-columns:1fr}
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
<section class="page-hero">
  <div class="container">
    <div class="page-hero-inner">
      <div class="hero-badge">📬 Get In Touch</div>
      <h1 class="page-hero-title">We are a team, not a ticketing system.</h1>
      <p class="page-hero-sub">Whether you are a student with a question, a company exploring a partnership, or an investor who wants to talk — reach out directly. We respond within one business day.</p>
    </div>
  </div>
</section>

<!-- MAIN -->
<div class="container">
  <div class="contact-layout">

    <!-- FORM -->
    <div class="form-card">
      <div class="form-card-header">
        <div class="form-card-title">Send Us a Message</div>
        <div class="form-card-sub">Fill in the form below — we respond to every message within 24 hours.</div>
      </div>
      <div class="form-card-body">

        <?php if($sent === '1'): ?>
          <div class="alert alert-success">
            <span class="alert-icon">✅</span>
            <div><strong>Message received.</strong> We will get back to you within one business day.</div>
          </div>
        <?php endif; ?>
        <?php if($error): ?>
          <div class="alert alert-error">
            <span class="alert-icon">⚠️</span>
            <div><?php echo htmlspecialchars($error); ?></div>
          </div>
        <?php endif; ?>

        <form method="POST" action="/internhub/actions/contact_action.php">
          <div class="field-row">
            <div class="field">
              <label class="field-label">Full Name <span>*</span></label>
              <input class="field-input" type="text" name="name" placeholder="e.g. Brian Oneka" required/>
            </div>
            <div class="field">
              <label class="field-label">Email Address <span>*</span></label>
              <input class="field-input" type="email" name="email" placeholder="your@email.com" required/>
            </div>
          </div>

          <div class="field-row">
            <div class="field">
              <label class="field-label">I am a</label>
              <select class="field-select" name="type">
                <option value="student">UICT Student</option>
                <option value="company">Company / Organisation</option>
                <option value="investor">Investor / Partner</option>
                <option value="other">Other</option>
              </select>
            </div>
            <div class="field">
              <label class="field-label">Subject <span>*</span></label>
              <select class="field-select" name="subject" required>
                <option value="">Select a topic</option>
                <option value="internship-listing">Internship Listing Query</option>
                <option value="readiness-program">Readiness Program</option>
                <option value="company-partnership">Company Partnership</option>
                <option value="investment">Investment &amp; Funding</option>
                <option value="technical">Technical Issue</option>
                <option value="other">Other</option>
              </select>
            </div>
          </div>

          <div class="field">
            <label class="field-label">Message <span>*</span></label>
            <textarea class="field-textarea" name="message" placeholder="Tell us what you need and we will respond promptly..." required></textarea>
            <div class="field-hint">Please be specific — the more detail you give, the faster we can help.</div>
          </div>

          <button type="submit" class="btn-submit">
            Send Message
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M2 8h12M10 4l4 4-4 4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
          </button>
        </form>
      </div>
    </div>

    <!-- SIDEBAR -->
    <div class="contact-sidebar">

      <div class="info-card">
        <div class="info-card-header">
          <div class="info-card-title">Contact Details</div>
        </div>
        <div class="info-rows">
          <div class="info-row">
            <div class="info-icon">📧</div>
            <div>
              <div class="info-label">Email</div>
              <div class="info-value"><a href="mailto:hello@internship.uict.ac.ug">hello@internship.uict.ac.ug</a></div>
            </div>
          </div>
          <div class="info-row">
            <div class="info-icon">📱</div>
            <div>
              <div class="info-label">WhatsApp / Phone</div>
              <div class="info-value"><a href="tel:+256700000000">+256 700 000 000</a></div>
            </div>
          </div>
          <div class="info-row">
            <div class="info-icon">📍</div>
            <div>
              <div class="info-label">Location</div>
              <div class="info-value">Computer Science Department<br/>UICT Nakawa, Kampala</div>
            </div>
          </div>
          <div class="info-row">
            <div class="info-icon">🎓</div>
            <div>
              <div class="info-label">Institution</div>
              <div class="info-value">Uganda Institute of Information &amp; Communications Technology</div>
            </div>
          </div>
        </div>
      </div>

      <div class="hours-card">
        <?php
        $dayNum = (int)date('N'); // 1=Mon, 7=Sun
        $days = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
        $today = $days[$dayNum-1];
        ?>
        <div class="hours-eyebrow">⏰ Response Hours</div>
        <div class="hours-title">When we are available</div>
        <div class="hours-rows">
          <?php foreach($days as $i => $d): $isToday = ($d === $today); ?>
          <div class="hours-row <?php echo $isToday?'today':''; ?>">
            <span class="hours-day"><?php echo $d; ?><?php echo $isToday?' (today)':''; ?></span>
            <span class="hours-time"><?php echo $i < 5 ? '8:00 AM – 6:00 PM' : ($i === 5 ? '10:00 AM – 2:00 PM' : 'Closed'); ?></span>
          </div>
          <?php endforeach; ?>
        </div>
      </div>

      <div class="info-card">
        <div class="info-card-header">
          <div class="info-card-title">Quick Links</div>
        </div>
        <div class="info-rows">
          <div class="info-row">
            <div class="info-icon">🏢</div>
            <div>
              <div class="info-label">Companies</div>
              <div class="info-value"><a href="/internhub/partners.php">View listing packages →</a></div>
            </div>
          </div>
          <div class="info-row">
            <div class="info-icon">🎓</div>
            <div>
              <div class="info-label">Students</div>
              <div class="info-value"><a href="/internhub/readiness.php">Readiness Program →</a></div>
            </div>
          </div>
          <div class="info-row">
            <div class="info-icon">💼</div>
            <div>
              <div class="info-label">Internships</div>
              <div class="info-value"><a href="/internhub/listings.php">Browse all listings →</a></div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>

  <!-- FAQ -->
  <div class="faq-section" id="faq">
    <div class="faq-header">
      <div class="s-eyebrow">FAQ</div>
      <h2 class="s-title">Common questions answered.</h2>
    </div>
    <div class="faq-grid">
      <?php
      $faqs=[
        ['Who can register on the platform?','Only current UICT students with a valid @stu.uict.ac.ug institutional email address. This restriction protects the quality of applicants that companies receive.'],
        ['How much does it cost to post an internship?','Standard listings are UGX 150,000 for 60 days. Featured listings with priority placement and Readiness-certified candidate filtering are UGX 300,000 for 90 days. Annual partner pricing is custom — contact us.'],
        ['How long does admin review take?','Standard review is completed within one business day. Featured and Annual partner listings receive same-day review.'],
        ['What is the Readiness Program?','A structured pre-internship preparation course covering CV writing, professional conduct, workplace communication, and interview skills. Students pay UGX 30,000–50,000 to enrol. See the Readiness page for full details.'],
        ['Can companies outside Kampala post listings?','Yes. Any registered Ugandan organisation can post an internship — regardless of location. Students apply directly to your email so geography is not a barrier.'],
        ['What payment methods do you accept?','We accept MTN Mobile Money and Airtel Money. No bank transfer or card payment is required.'],
        ['What happens if my listing gets rejected?','Our admin team will contact you with the specific reason and give you the opportunity to resubmit after addressing the issue. Common reasons include unverifiable contact details or unclear role descriptions.'],
        ['How do I enrol in the Readiness Program?','Visit the Readiness Program page, choose your cohort date, and complete payment via Mobile Money. Confirmation and onboarding details are sent to your UICT email within 24 hours.'],
      ];
      foreach($faqs as [$q,$a]):
      ?>
      <div class="faq-item">
        <div class="faq-q"><?php echo $q; ?></div>
        <div class="faq-a"><?php echo $a; ?></div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>

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
