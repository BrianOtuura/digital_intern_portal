<?php
$activePage = 'home';
$pageTitle  = 'Home';
require_once 'includes/db.php';
require_once 'includes/header.php';

$totalListings  = $pdo->query("SELECT COUNT(*) FROM internships WHERE status='approved'")->fetchColumn();
$totalCompanies = $pdo->query("SELECT COUNT(DISTINCT company) FROM internships WHERE status='approved'")->fetchColumn();
$totalStudents  = $pdo->query("SELECT COUNT(*) FROM users WHERE role='student'")->fetchColumn();
$stmt           = $pdo->query("SELECT * FROM internships WHERE status='approved' ORDER BY created_at DESC LIMIT 3");
$featured       = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Digital Internship Portal — UICT's Career Launchpad</title>
<link rel="preconnect" href="https://fonts.googleapis.com"/>
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,300;0,9..144,600;0,9..144,700;1,9..144,300&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
<link rel="stylesheet" href="/internhub/css/style.css">
<style>
/* ── Reset & Tokens ─────────────────────────────────────────────────── */
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{
  --teal-900:#062E22;
  --teal-800:#0B4D3B;
  --teal-700:#0E6B52;
  --teal-600:#128A69;
  --teal-400:#1DB885;
  --teal-200:#8EDFC5;
  --teal-100:#C5F0E3;
  --teal-50: #EDF9F5;
  --gold:    #D4A017;
  --gold-lt: #FBF3D9;
  --gold-dk: #A67C00;
  --white:   #FFFFFF;
  --off:     #F8FAF9;
  --grey-100:#EEF1EF;
  --grey-200:#D8DFDB;
  --grey-400:#8A9E96;
  --grey-600:#4A5E56;
  --ink:     #0D1F19;
  --shadow-sm:0 1px 4px rgba(6,46,34,.07);
  --shadow-md:0 4px 20px rgba(6,46,34,.10);
  --shadow-lg:0 12px 48px rgba(6,46,34,.13);
  --shadow-xl:0 24px 80px rgba(6,46,34,.16);
  --r-sm:6px;
  --r-md:12px;
  --r-lg:20px;
  --r-xl:32px;
  --ease:cubic-bezier(.4,0,.2,1);
  --t:.2s;
}
html{scroll-behavior:smooth;font-size:16px}
body{font-family:'Plus Jakarta Sans',sans-serif;background:var(--white);color:var(--ink);overflow-x:hidden;-webkit-font-smoothing:antialiased}
img{display:block;max-width:100%}
a{text-decoration:none;color:inherit}
::-webkit-scrollbar{width:5px}
::-webkit-scrollbar-track{background:var(--off)}
::-webkit-scrollbar-thumb{background:var(--grey-200);border-radius:99px}

/* ── NAV ──────────────────────────────────────────────────────────────── */
.nav{
  position:sticky;top:0;z-index:200;
  height:68px;display:flex;align-items:center;justify-content:space-between;
  padding:0 2.5rem;
  background:rgba(255,255,255,.95);
  backdrop-filter:blur(14px);
  border-bottom:1px solid var(--grey-100);
  box-shadow:var(--shadow-sm);
}
.nav-brand{
  display:flex;align-items:center;gap:10px;
  font-family:'Fraunces',serif;font-size:1.1rem;font-weight:700;
  color:var(--teal-800);letter-spacing:-.01em;
}
.nav-brand-dot{
  width:8px;height:8px;border-radius:50%;
  background:var(--teal-600);
  box-shadow:0 0 0 3px var(--teal-100);
}
.nav-links{display:flex;align-items:center;gap:4px}
.nav-links a{
  font-size:.875rem;font-weight:500;color:var(--grey-600);
  padding:6px 13px;border-radius:var(--r-sm);
  transition:color var(--t) var(--ease),background var(--t) var(--ease);
}
.nav-links a:hover{color:var(--ink);background:var(--off)}
.nav-links a.active{color:var(--teal-700);font-weight:600}
.nav-cta{
  background:var(--teal-800)!important;color:#fff!important;
  font-weight:600!important;border-radius:var(--r-sm)!important;
  padding:8px 18px!important;
  box-shadow:0 2px 8px rgba(11,77,59,.28)!important;
  transition:all var(--t) var(--ease)!important;
}
.nav-cta:hover{background:var(--teal-900)!important;transform:translateY(-1px)!important}

/* ── HERO ─────────────────────────────────────────────────────────────── */
.hero{
  background:var(--teal-900);
  position:relative;overflow:hidden;
  padding:7rem 2.5rem 5rem;
  min-height:88vh;
  display:grid;grid-template-columns:1fr 1fr;gap:4rem;
  align-items:center;max-width:1200px;margin:0 auto;
}
/* Textured mesh bg on the section itself */
.hero-section{
  background:var(--teal-900);
  position:relative;overflow:hidden;
}
.hero-section::before{
  content:'';position:absolute;inset:0;
  background:
    radial-gradient(ellipse 70% 60% at 65% 40%,rgba(18,138,105,.35) 0%,transparent 70%),
    radial-gradient(ellipse 40% 50% at 15% 80%,rgba(11,77,59,.4) 0%,transparent 60%),
    radial-gradient(ellipse 30% 30% at 90% 10%,rgba(29,184,133,.1) 0%,transparent 50%);
  pointer-events:none;
}
.hero-section::after{
  content:'';position:absolute;inset:0;
  background-image:
    linear-gradient(rgba(255,255,255,.025) 1px,transparent 1px),
    linear-gradient(90deg,rgba(255,255,255,.025) 1px,transparent 1px);
  background-size:52px 52px;
  pointer-events:none;
}
.hero{position:relative;z-index:1}

.hero-eyebrow{
  display:inline-flex;align-items:center;gap:8px;
  font-size:.72rem;font-weight:600;letter-spacing:.14em;
  text-transform:uppercase;color:var(--teal-400);
  background:rgba(29,184,133,.12);
  border:1px solid rgba(29,184,133,.25);
  padding:5px 14px;border-radius:99px;
  margin-bottom:1.6rem;
}
.hero-eyebrow-dot{
  width:5px;height:5px;border-radius:50%;
  background:var(--teal-400);
  animation:pulse 2s ease-in-out infinite;
}
@keyframes pulse{
  0%,100%{opacity:1;transform:scale(1)}
  50%{opacity:.4;transform:scale(.75)}
}
.hero-title{
  font-family:'Fraunces',serif;
  font-size:clamp(2.6rem,5vw,3.8rem);
  font-weight:700;line-height:1.08;
  letter-spacing:-.03em;color:#fff;
  margin-bottom:1.4rem;
}
.hero-title em{font-style:italic;color:var(--teal-400)}
.hero-body{
  font-size:1.05rem;color:rgba(255,255,255,.68);
  line-height:1.78;max-width:480px;
  margin-bottom:2.2rem;
  font-weight:300;
}
.hero-actions{display:flex;gap:.9rem;flex-wrap:wrap;margin-bottom:3rem}
.btn-solid{
  display:inline-flex;align-items:center;gap:8px;
  background:var(--gold);color:var(--ink);
  font-family:'Plus Jakarta Sans',sans-serif;
  font-weight:700;font-size:.9rem;
  padding:13px 28px;border-radius:var(--r-sm);
  border:none;cursor:pointer;
  box-shadow:0 4px 16px rgba(212,160,23,.35);
  transition:all var(--t) var(--ease);
}
.btn-solid:hover{background:var(--gold-dk);transform:translateY(-2px);box-shadow:0 6px 24px rgba(212,160,23,.4)}
.btn-ghost-light{
  display:inline-flex;align-items:center;gap:8px;
  background:rgba(255,255,255,.1);color:#fff;
  font-family:'Plus Jakarta Sans',sans-serif;
  font-weight:500;font-size:.9rem;
  padding:13px 28px;border-radius:var(--r-sm);
  border:1px solid rgba(255,255,255,.2);
  transition:all var(--t) var(--ease);
}
.btn-ghost-light:hover{background:rgba(255,255,255,.18);border-color:rgba(255,255,255,.35)}

.hero-stats{
  display:flex;align-items:center;gap:2rem;
  padding-top:2rem;border-top:1px solid rgba(255,255,255,.1);
}
.hero-stat{display:flex;flex-direction:column;gap:3px}
.hero-stat-num{
  font-family:'Fraunces',serif;
  font-size:1.8rem;font-weight:700;color:#fff;line-height:1;
}
.hero-stat-num span{color:var(--teal-400)}
.hero-stat-label{font-size:.75rem;color:rgba(255,255,255,.45);letter-spacing:.02em}
.hero-stat-div{width:1px;height:36px;background:rgba(255,255,255,.12)}

/* Hero right — floating cards */
.hero-cards{display:flex;flex-direction:column;gap:1rem;position:relative;z-index:1}
.hcard{
  background:rgba(255,255,255,.07);
  backdrop-filter:blur(20px);
  border:1px solid rgba(255,255,255,.13);
  border-radius:var(--r-lg);
  padding:1.2rem 1.4rem;
  cursor:pointer;
  transition:all var(--t) var(--ease);
}
.hcard:hover{background:rgba(255,255,255,.11);border-color:rgba(29,184,133,.35);transform:translateX(-4px)}
.hcard-mid{margin-left:2rem}
.hcard-head{display:flex;justify-content:space-between;align-items:center;margin-bottom:.7rem}
.hcard-co{display:flex;align-items:center;gap:9px}
.hcard-logo{
  width:32px;height:32px;border-radius:var(--r-sm);flex-shrink:0;
  display:flex;align-items:center;justify-content:center;
  font-family:'Fraunces',serif;font-weight:700;font-size:.75rem;color:#fff;
}
.hcard-company{font-size:.72rem;color:rgba(255,255,255,.5);margin-bottom:1px}
.hcard-title{font-family:'Fraunces',serif;font-weight:600;font-size:.9rem;color:#fff}
.hcard-badge{
  font-size:.68rem;font-weight:600;padding:2px 9px;
  border-radius:99px;white-space:nowrap;
  background:rgba(29,184,133,.18);color:var(--teal-400);
  border:1px solid rgba(29,184,133,.28);
}
.hcard-badge-urgent{background:rgba(212,160,23,.18);color:var(--gold);border-color:rgba(212,160,23,.3)}
.hcard-tags{display:flex;gap:5px;flex-wrap:wrap}
.hcard-tag{
  font-size:.68rem;padding:2px 8px;border-radius:99px;
  background:rgba(255,255,255,.07);color:rgba(255,255,255,.5);
  border:1px solid rgba(255,255,255,.1);
}

/* ── TRUSTED STRIP ────────────────────────────────────────────────────── */
.trusted{
  background:var(--off);
  border-top:1px solid var(--grey-100);
  border-bottom:1px solid var(--grey-100);
  padding:1.8rem 2.5rem;
}
.trusted-inner{max-width:1200px;margin:0 auto}
.trusted-label{
  text-align:center;font-size:.7rem;font-weight:600;
  text-transform:uppercase;letter-spacing:.14em;
  color:var(--grey-400);margin-bottom:1.2rem;
}
.trusted-logos{display:flex;flex-wrap:wrap;gap:.7rem;justify-content:center}
.trusted-pill{
  display:flex;align-items:center;gap:8px;
  background:var(--white);border:1px solid var(--grey-200);
  border-radius:99px;padding:5px 14px 5px 5px;
  font-size:.8rem;font-weight:500;color:var(--grey-600);
  box-shadow:var(--shadow-sm);
  transition:all var(--t) var(--ease);
}
.trusted-pill:hover{box-shadow:var(--shadow-md);transform:translateY(-1px)}
.trusted-icon{
  width:24px;height:24px;border-radius:50%;flex-shrink:0;
  display:flex;align-items:center;justify-content:center;
  font-family:'Fraunces',serif;font-weight:700;font-size:.6rem;color:#fff;
}

/* ── SECTION SHARED ───────────────────────────────────────────────────── */
.section{padding:6rem 2.5rem}
.container{max-width:1200px;margin:0 auto}
.s-eyebrow{
  font-size:.7rem;font-weight:700;letter-spacing:.16em;
  text-transform:uppercase;color:var(--teal-600);
  margin-bottom:.5rem;
}
.s-title{
  font-family:'Fraunces',serif;
  font-size:clamp(1.8rem,3.5vw,2.6rem);
  font-weight:700;color:var(--ink);
  line-height:1.12;letter-spacing:-.025em;
  margin-bottom:.8rem;
}
.s-sub{font-size:1rem;color:var(--grey-600);line-height:1.72;max-width:520px}

/* ── PROBLEM SECTION ──────────────────────────────────────────────────── */
.problem-section{background:var(--white)}
.problem-grid{
  display:grid;grid-template-columns:1fr 1fr;gap:4rem;
  align-items:center;margin-top:4rem;
}
.problem-image{
  border-radius:var(--r-xl);overflow:hidden;
  box-shadow:var(--shadow-xl);height:480px;
  position:relative;
}
.problem-image img{width:100%;height:100%;object-fit:cover}
.problem-image-overlay{
  position:absolute;inset:0;
  background:linear-gradient(to top,rgba(6,46,34,.7) 0%,transparent 55%);
}
.problem-image-stat{
  position:absolute;bottom:1.5rem;left:1.5rem;right:1.5rem;
  background:rgba(6,46,34,.85);backdrop-filter:blur(12px);
  border:1px solid rgba(29,184,133,.25);
  border-radius:var(--r-md);padding:1.2rem;
}
.problem-image-stat-num{
  font-family:'Fraunces',serif;font-size:2.2rem;font-weight:700;
  color:#fff;line-height:1;margin-bottom:4px;
}
.problem-image-stat-num span{color:var(--teal-400)}
.problem-image-stat-label{font-size:.82rem;color:rgba(255,255,255,.65)}
.problem-items{display:flex;flex-direction:column;gap:1rem}
.problem-item{
  display:flex;gap:1.2rem;align-items:flex-start;
  background:var(--off);border:1px solid var(--grey-100);
  border-radius:var(--r-md);padding:1.3rem;
  transition:all var(--t) var(--ease);
}
.problem-item:hover{border-color:var(--teal-200);box-shadow:var(--shadow-md);transform:translateX(4px)}
.problem-item-icon{
  width:40px;height:40px;border-radius:var(--r-sm);flex-shrink:0;
  background:var(--teal-50);border:1px solid var(--teal-100);
  display:flex;align-items:center;justify-content:center;font-size:1.2rem;
}
.problem-item-title{
  font-family:'Fraunces',serif;font-weight:600;font-size:.98rem;
  color:var(--ink);margin-bottom:.3rem;
}
.problem-item-body{font-size:.87rem;color:var(--grey-600);line-height:1.65}

/* ── SOLUTION ─────────────────────────────────────────────────────────── */
.solution-section{background:var(--off);border-top:1px solid var(--grey-100)}
.solution-grid{
  display:grid;grid-template-columns:repeat(3,1fr);
  gap:1.5rem;margin-top:3.5rem;
}
.solution-card{
  background:var(--white);border:1px solid var(--grey-200);
  border-radius:var(--r-lg);padding:2rem;
  transition:all .25s var(--ease);
  position:relative;overflow:hidden;
}
.solution-card::after{
  content:'';position:absolute;bottom:0;left:0;right:0;height:3px;
  background:linear-gradient(90deg,var(--teal-600),var(--teal-400));
  transform:scaleX(0);transform-origin:left;
  transition:transform .25s var(--ease);
}
.solution-card:hover{transform:translateY(-4px);box-shadow:var(--shadow-lg);border-color:var(--grey-200)}
.solution-card:hover::after{transform:scaleX(1)}
.solution-card-featured{
  background:var(--teal-800);border-color:var(--teal-700);
}
.solution-card-featured::after{background:linear-gradient(90deg,var(--gold),var(--gold-dk))}
.solution-icon{
  width:48px;height:48px;border-radius:var(--r-md);
  display:flex;align-items:center;justify-content:center;
  font-size:1.4rem;margin-bottom:1.4rem;
  background:var(--teal-50);border:1px solid var(--teal-100);
}
.solution-card-featured .solution-icon{background:rgba(255,255,255,.1);border-color:rgba(255,255,255,.15)}
.solution-card-label{
  font-size:.68rem;font-weight:700;letter-spacing:.12em;
  text-transform:uppercase;color:var(--teal-600);
  margin-bottom:.5rem;
}
.solution-card-featured .solution-card-label{color:var(--teal-400)}
.solution-card-title{
  font-family:'Fraunces',serif;font-weight:700;font-size:1.15rem;
  color:var(--ink);margin-bottom:.6rem;
}
.solution-card-featured .solution-card-title{color:#fff}
.solution-card-body{font-size:.875rem;color:var(--grey-600);line-height:1.68;margin-bottom:1.4rem}
.solution-card-featured .solution-card-body{color:rgba(255,255,255,.68)}
.solution-list{list-style:none;display:flex;flex-direction:column;gap:.5rem;flex:1}
.solution-list li{
  font-size:.84rem;color:var(--grey-600);
  display:flex;align-items:flex-start;gap:8px;line-height:1.5;
}
.solution-list li::before{
  content:'';width:16px;height:16px;border-radius:50%;flex-shrink:0;
  background:var(--teal-50);border:1px solid var(--teal-200);
  background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='10' viewBox='0 0 10 10'%3E%3Cpath d='M2 5l2 2 4-4' stroke='%230E6B52' stroke-width='1.5' fill='none' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
  background-repeat:no-repeat;background-position:center;
  margin-top:1px;
}
.solution-card-featured .solution-list li{color:rgba(255,255,255,.75)}
.solution-card-featured .solution-list li::before{
  background-color:rgba(255,255,255,.12);border-color:rgba(255,255,255,.2);
  background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='10' viewBox='0 0 10 10'%3E%3Cpath d='M2 5l2 2 4-4' stroke='%231DB885' stroke-width='1.5' fill='none' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
}
.solution-link{
  display:inline-flex;align-items:center;gap:6px;
  font-size:.875rem;font-weight:600;
  color:var(--teal-700);margin-top:auto;
  transition:gap var(--t) var(--ease);
}
.solution-link:hover{gap:10px}
.solution-card-featured .solution-link{color:var(--gold)}

/* ── HOW IT WORKS ─────────────────────────────────────────────────────── */
.hiw-section{background:var(--white);border-top:1px solid var(--grey-100)}
.hiw-steps{
  display:grid;grid-template-columns:repeat(4,1fr);
  gap:0;margin-top:4rem;position:relative;
}
.hiw-steps::before{
  content:'';position:absolute;top:28px;left:8%;right:8%;
  height:1px;background:var(--grey-200);z-index:0;
}
.hiw-step{text-align:center;padding:0 1.5rem;position:relative;z-index:1}
.hiw-num{
  width:56px;height:56px;border-radius:50%;
  background:var(--white);border:2px solid var(--grey-200);
  display:flex;align-items:center;justify-content:center;
  margin:0 auto 1.5rem;
  box-shadow:var(--shadow-sm);
  font-family:'Fraunces',serif;font-weight:700;font-size:1.1rem;
  color:var(--teal-700);
  transition:all var(--t) var(--ease);
}
.hiw-step:hover .hiw-num{border-color:var(--teal-600);box-shadow:0 0 0 5px var(--teal-50)}
.hiw-title{
  font-family:'Fraunces',serif;font-weight:600;font-size:.98rem;
  color:var(--ink);margin-bottom:.5rem;
}
.hiw-body{font-size:.83rem;color:var(--grey-600);line-height:1.6}

/* ── READINESS TEASER ─────────────────────────────────────────────────── */
.readiness-section{
  background:var(--teal-900);
  position:relative;overflow:hidden;
  padding:6rem 2.5rem;
}
.readiness-section::before{
  content:'';position:absolute;inset:0;
  background:
    radial-gradient(ellipse 60% 70% at 80% 50%,rgba(18,138,105,.3) 0%,transparent 65%),
    radial-gradient(ellipse 40% 50% at 5% 30%,rgba(11,77,59,.5) 0%,transparent 55%);
  pointer-events:none;
}
.readiness-inner{
  max-width:1200px;margin:0 auto;
  display:grid;grid-template-columns:1fr 1fr;
  gap:5rem;align-items:center;
  position:relative;z-index:1;
}
.readiness-eyebrow{
  display:inline-flex;align-items:center;gap:8px;
  font-size:.7rem;font-weight:700;letter-spacing:.14em;
  text-transform:uppercase;color:var(--gold);
  background:rgba(212,160,23,.12);border:1px solid rgba(212,160,23,.25);
  padding:5px 14px;border-radius:99px;margin-bottom:1.4rem;
}
.readiness-title{
  font-family:'Fraunces',serif;font-size:clamp(1.9rem,3.5vw,2.8rem);
  font-weight:700;color:#fff;line-height:1.1;
  letter-spacing:-.025em;margin-bottom:1rem;
}
.readiness-title em{font-style:italic;color:var(--gold)}
.readiness-body{
  font-size:1rem;color:rgba(255,255,255,.68);
  line-height:1.75;margin-bottom:2rem;font-weight:300;
}
.readiness-modules{display:flex;flex-direction:column;gap:.8rem}
.readiness-module{
  display:flex;align-items:center;gap:12px;
  background:rgba(255,255,255,.06);
  border:1px solid rgba(255,255,255,.1);
  border-radius:var(--r-md);padding:1rem 1.2rem;
  transition:all var(--t) var(--ease);
}
.readiness-module:hover{background:rgba(255,255,255,.1);border-color:rgba(29,184,133,.3)}
.readiness-module-icon{font-size:1.3rem;flex-shrink:0}
.readiness-module-title{
  font-family:'Fraunces',serif;font-weight:600;font-size:.95rem;
  color:#fff;margin-bottom:2px;
}
.readiness-module-sub{font-size:.8rem;color:rgba(255,255,255,.5)}
.readiness-cta{display:flex;flex-direction:column;gap:1rem;margin-top:2rem}
.btn-gold{
  display:inline-flex;align-items:center;gap:8px;
  background:var(--gold);color:var(--ink);
  font-family:'Plus Jakarta Sans',sans-serif;
  font-weight:700;font-size:.9rem;
  padding:13px 28px;border-radius:var(--r-sm);
  border:none;cursor:pointer;
  box-shadow:0 4px 16px rgba(212,160,23,.3);
  transition:all var(--t) var(--ease);
}
.btn-gold:hover{background:var(--gold-dk);transform:translateY(-2px)}
.readiness-note{font-size:.82rem;color:rgba(255,255,255,.4)}

/* ── PARTNER CTA ──────────────────────────────────────────────────────── */
.partner-section{background:var(--off);border-top:1px solid var(--grey-100);padding:6rem 2.5rem}
.partner-card{
  background:var(--white);border:1px solid var(--grey-200);
  border-radius:var(--r-xl);padding:4rem;
  display:grid;grid-template-columns:1fr 1fr;
  gap:4rem;align-items:center;
  box-shadow:var(--shadow-md);
  position:relative;overflow:hidden;
}
.partner-card::before{
  content:'';position:absolute;top:-40%;right:-10%;
  width:400px;height:400px;border-radius:50%;
  background:radial-gradient(circle,rgba(18,138,105,.06),transparent 70%);
  pointer-events:none;
}
.partner-eyebrow{
  font-size:.7rem;font-weight:700;letter-spacing:.14em;
  text-transform:uppercase;color:var(--teal-600);
  margin-bottom:.8rem;
}
.partner-title{
  font-family:'Fraunces',serif;font-size:clamp(1.7rem,3vw,2.3rem);
  font-weight:700;color:var(--ink);
  line-height:1.12;margin-bottom:.8rem;letter-spacing:-.02em;
}
.partner-body{font-size:.95rem;color:var(--grey-600);line-height:1.72;margin-bottom:1.8rem}
.partner-actions{display:flex;gap:.9rem;flex-wrap:wrap}
.btn-primary{
  display:inline-flex;align-items:center;gap:7px;
  background:var(--teal-800);color:#fff;
  font-family:'Plus Jakarta Sans',sans-serif;
  font-weight:600;font-size:.9rem;
  padding:12px 24px;border-radius:var(--r-sm);
  border:none;cursor:pointer;
  box-shadow:0 2px 8px rgba(11,77,59,.25);
  transition:all var(--t) var(--ease);
}
.btn-primary:hover{background:var(--teal-900);transform:translateY(-1px)}
.btn-outline{
  display:inline-flex;align-items:center;gap:7px;
  background:transparent;color:var(--teal-800);
  font-family:'Plus Jakarta Sans',sans-serif;
  font-weight:600;font-size:.9rem;
  padding:12px 24px;border-radius:var(--r-sm);
  border:1.5px solid var(--teal-800);
  transition:all var(--t) var(--ease);
}
.btn-outline:hover{background:var(--teal-50)}
.partner-benefits{display:flex;flex-direction:column;gap:.8rem}
.partner-benefit{
  display:flex;align-items:flex-start;gap:12px;
  padding:1rem 1.2rem;
  background:var(--off);border:1px solid var(--grey-100);
  border-radius:var(--r-md);
  transition:all var(--t) var(--ease);
}
.partner-benefit:hover{border-color:var(--teal-200);background:var(--teal-50)}
.partner-benefit-icon{font-size:1.2rem;flex-shrink:0;margin-top:1px}
.partner-benefit-title{
  font-family:'Fraunces',serif;font-weight:600;font-size:.92rem;
  color:var(--ink);margin-bottom:2px;
}
.partner-benefit-body{font-size:.82rem;color:var(--grey-600);line-height:1.55}

/* ── TESTIMONIALS ─────────────────────────────────────────────────────── */
.testimonials-section{background:var(--white);border-top:1px solid var(--grey-100);padding:6rem 2.5rem}
.testimonials-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:1.5rem;margin-top:3.5rem}
.testimonial{
  background:var(--off);border:1px solid var(--grey-100);
  border-radius:var(--r-lg);padding:2rem;
  display:flex;flex-direction:column;
  transition:all var(--t) var(--ease);
}
.testimonial:hover{box-shadow:var(--shadow-md);border-color:var(--grey-200);transform:translateY(-3px)}
.testimonial-stars{color:var(--gold);font-size:.85rem;letter-spacing:2px;margin-bottom:1rem}
.testimonial-text{
  font-size:.92rem;color:var(--grey-600);
  line-height:1.78;margin-bottom:1.8rem;flex:1;
  font-style:italic;
}
.testimonial-author{display:flex;align-items:center;gap:12px}
.testimonial-avatar{
  width:40px;height:40px;border-radius:50%;flex-shrink:0;
  display:flex;align-items:center;justify-content:center;
  font-family:'Fraunces',serif;font-weight:700;font-size:.82rem;color:#fff;
}
.testimonial-name{font-family:'Fraunces',serif;font-weight:600;font-size:.9rem;color:var(--ink)}
.testimonial-role{font-size:.75rem;color:var(--grey-400);margin-top:1px}

/* ── FINAL CTA ────────────────────────────────────────────────────────── */
.finalcta-section{
  background:var(--teal-900);padding:6rem 2.5rem;
  position:relative;overflow:hidden;
}
.finalcta-section::before{
  content:'';position:absolute;inset:0;
  background:
    radial-gradient(ellipse 50% 80% at 20% 50%,rgba(18,138,105,.35) 0%,transparent 60%),
    radial-gradient(ellipse 40% 60% at 85% 30%,rgba(11,77,59,.4) 0%,transparent 55%);
  pointer-events:none;
}
.finalcta-inner{
  max-width:680px;margin:0 auto;text-align:center;
  position:relative;z-index:1;
}
.finalcta-eyebrow{
  display:inline-flex;align-items:center;gap:7px;
  font-size:.7rem;font-weight:700;letter-spacing:.14em;
  text-transform:uppercase;color:var(--teal-400);
  background:rgba(29,184,133,.12);border:1px solid rgba(29,184,133,.25);
  padding:5px 14px;border-radius:99px;margin-bottom:1.5rem;
}
.finalcta-title{
  font-family:'Fraunces',serif;font-size:clamp(2rem,4vw,3rem);
  font-weight:700;color:#fff;line-height:1.1;
  letter-spacing:-.025em;margin-bottom:1rem;
}
.finalcta-body{
  font-size:1rem;color:rgba(255,255,255,.65);
  line-height:1.72;margin-bottom:2.5rem;font-weight:300;
}
.finalcta-actions{display:flex;gap:.9rem;flex-wrap:wrap;justify-content:center}

/* ── FOOTER ───────────────────────────────────────────────────────────── */
.footer{
  background:#040F0A;
  padding:5rem 2.5rem 2.5rem;
}
.footer-top{
  display:grid;grid-template-columns:2.5fr 1fr 1fr 1.2fr;
  gap:3rem;padding-bottom:3rem;max-width:1200px;margin:0 auto;
  border-bottom:1px solid rgba(255,255,255,.07);
  margin-bottom:2rem;
}
.footer-brand-name{
  font-family:'Fraunces',serif;font-size:1.1rem;font-weight:700;
  color:#fff;margin-bottom:.8rem;
}
.footer-brand-desc{
  font-size:.85rem;color:rgba(255,255,255,.4);
  line-height:1.7;margin-bottom:1.2rem;max-width:260px;
}
.footer-badge{
  display:inline-flex;align-items:center;gap:7px;
  background:rgba(29,184,133,.1);border:1px solid rgba(29,184,133,.2);
  border-radius:99px;padding:4px 12px;
  font-size:.72rem;font-weight:600;color:var(--teal-400);
}
.footer-badge-dot{width:5px;height:5px;border-radius:50%;background:var(--teal-400)}
.footer-col-title{
  font-size:.7rem;font-weight:700;text-transform:uppercase;
  letter-spacing:.12em;color:rgba(255,255,255,.3);
  margin-bottom:1.2rem;
}
.footer-col a,.footer-col span{
  display:block;font-size:.86rem;
  color:rgba(255,255,255,.5);text-decoration:none;
  margin-bottom:.55rem;
  transition:color var(--t) var(--ease);
}
.footer-col a:hover{color:rgba(255,255,255,.9)}
.footer-bottom{
  max-width:1200px;margin:0 auto;
  display:flex;justify-content:space-between;align-items:center;
  flex-wrap:wrap;gap:1rem;
  font-size:.78rem;color:rgba(255,255,255,.22);
}

/* ── RESPONSIVE ───────────────────────────────────────────────────────── */
@media(max-width:1024px){
  .hero{grid-template-columns:1fr;min-height:auto;padding:5rem 2rem 4rem}
  .hero-cards{display:none}
  .solution-grid{grid-template-columns:1fr 1fr}
  .hiw-steps{grid-template-columns:repeat(2,1fr)}
  .hiw-steps::before{display:none}
  .readiness-inner{grid-template-columns:1fr;gap:3rem}
  .partner-card{grid-template-columns:1fr;gap:2.5rem;padding:2.5rem}
  .testimonials-grid{grid-template-columns:1fr 1fr}
  .footer-top{grid-template-columns:1fr 1fr;gap:2rem}
  .problem-grid{grid-template-columns:1fr;gap:2rem}
  .problem-image{height:300px}
}
@media(max-width:768px){
  .nav{padding:0 1.25rem}
  .nav-links a:not(.nav-cta){display:none}
  .section{padding:4rem 1.25rem}
  .solution-grid{grid-template-columns:1fr}
  .hiw-steps{grid-template-columns:1fr 1fr}
  .testimonials-grid{grid-template-columns:1fr}
  .partner-section{padding:4rem 1.25rem}
  .finalcta-section{padding:4rem 1.25rem}
  .footer{padding:3.5rem 1.25rem 2rem}
  .footer-top{grid-template-columns:1fr}
  .readiness-section{padding:4rem 1.25rem}
  .hero{padding:4rem 1.25rem 3rem}
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
    <a href="/internhub/index.php" class="active">Home</a>
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
<section class="hero-section">
<div class="hero">
  <div>
    <div class="hero-eyebrow">
      <span class="hero-eyebrow-dot"></span>
      Uganda · UICT Nakawa · Class of 2025
    </div>
    <h1 class="hero-title">
      A Digital<br/>
      <em>Structured</em> Internship<br/>
      Platform
    </h1>
    <p class="hero-body">
      We connect UICT students with verified internship
      opportunities from Ugandan organisations — and prepare them to
      actually succeed when they get there.
    </p>
    <div class="hero-actions">
      <a href="/internhub/listings.php" class="btn-solid">
        Browse Opportunities
        <svg width="14" height="14" viewBox="0 0 14 14" fill="none"><path d="M3 7h8M8 3.5L11.5 7 8 10.5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
      </a>
      <a href="/internhub/readiness.php" class="btn-ghost-light">
        Readiness Program
      </a>
    </div>
    <div class="hero-stats">
      <div class="hero-stat">
        <div class="hero-stat-num"><?php echo $totalListings; ?><span>+</span></div>
        <div class="hero-stat-label">Active Listings</div>
      </div>
      <div class="hero-stat-div"></div>
      <div class="hero-stat">
        <div class="hero-stat-num"><?php echo $totalCompanies; ?><span>+</span></div>
        <div class="hero-stat-label">Partner Companies</div>
      </div>
      <div class="hero-stat-div"></div>
      <div class="hero-stat">
        <div class="hero-stat-num"><?php echo max($totalStudents,1); ?><span>+</span></div>
        <div class="hero-stat-label">Registered Students</div>
      </div>
      <div class="hero-stat-div"></div>
      <div class="hero-stat">
        <div class="hero-stat-num">UGX<span> 0</span></div>
        <div class="hero-stat-label">Cost to Students</div>
      </div>
    </div>
  </div>

  <div class="hero-cards">
    <?php foreach(array_slice($featured,0,3) as $idx=>$c):
      $tags=array_slice(explode(',',$c['tags']),0,2);
      $dl=new DateTime($c['deadline']);$td=new DateTime();
      $days=(int)$td->diff($dl)->format('%r%a');
      $urgent=$days<=7;
    ?>
    <div class="hcard <?php echo $idx===1?'hcard-mid':''; ?>"
         onclick="location.href='/internhub/detail.php?id=<?php echo $c['id']; ?>'">
      <div class="hcard-head">
        <div class="hcard-co">
          <div class="hcard-logo" style="background:<?php echo htmlspecialchars($c['logo_color']); ?>">
            <?php echo htmlspecialchars($c['logo']); ?>
          </div>
          <div>
            <div class="hcard-company"><?php echo htmlspecialchars($c['company']); ?></div>
            <div class="hcard-title"><?php echo htmlspecialchars($c['title']); ?></div>
          </div>
        </div>
        <span class="hcard-badge <?php echo $urgent?'hcard-badge-urgent':''; ?>">
          <?php echo $urgent?'Closing Soon':'Open'; ?>
        </span>
      </div>
      <div class="hcard-tags">
        <?php foreach($tags as $t): ?>
          <span class="hcard-tag"><?php echo htmlspecialchars(trim($t)); ?></span>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</div>
</section>

<!-- TRUSTED -->
<section class="trusted">
  <div class="trusted-inner">
    <p class="trusted-label">Internships from verified Ugandan organisations</p>
    <div class="trusted-logos">
      <?php
      $partners=[['MTN Uganda','#FFD700','#1B3A2D'],['DFCU Bank','#003366','#fff'],
                 ['Airtel Uganda','#CC0000','#fff'],['Stanbic Bank','#003087','#fff'],
                 ['SafeBoda','#00A651','#fff'],['UTL','#0066CC','#fff'],
                 ['Yo! Uganda','#E31837','#fff'],['Ministry of ICT','#1A5C3A','#fff']];
      foreach($partners as [$name,$bg,$fg]):
        $w=explode(' ',$name);
        $init=strtoupper(substr($w[0],0,1).(isset($w[1])?substr($w[1],0,1):substr($w[0],1,1)));
      ?>
      <div class="trusted-pill">
        <div class="trusted-icon" style="background:<?php echo $bg; ?>;color:<?php echo $fg; ?>">
          <?php echo $init; ?>
        </div>
        <span><?php echo $name; ?></span>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- PROBLEM -->
<section class="section problem-section">
  <div class="container">
    <div class="problem-grid">
      <div class="problem-image">
        <img src="https://images.unsplash.com/photo-1523240795612-9a054b0db644?w=700&q=80" alt="Students"/>
        <div class="problem-image-overlay"></div>
        <div class="problem-image-stat">
          <div class="problem-image-stat-num">68<span>%</span></div>
          <div class="problem-image-stat-label">of UICT students report difficulty finding internships through official channels</div>
        </div>
      </div>
      <div>
        <div class="s-eyebrow">The Problem</div>
        <h2 class="s-title">A system that fails its students</h2>
        <p class="s-sub" style="margin-bottom:2rem">
          Uganda produces thousands of ICT graduates annually. Most arrive at the job market underprepared — not because they lack intelligence, but because the bridge between campus and industry has never been properly built.
        </p>
        <div class="problem-items">
          <div class="problem-item">
            <div class="problem-item-icon">📋</div>
            <div>
              <div class="problem-item-title">No centralised listing system</div>
              <div class="problem-item-body">Students rely on word of mouth, physical notice boards, and personal networks. Opportunities are distributed inequitably — favouring those with connections over those with capability.</div>
            </div>
          </div>
          <div class="problem-item">
            <div class="problem-item-icon">🎯</div>
            <div>
              <div class="problem-item-title">Students arrive unprepared</div>
              <div class="problem-item-body">Finding a placement is only half the problem. Many students secure internships but underperform because they have never been taught professional workplace expectations, CV writing, or interview skills.</div>
            </div>
          </div>
          <div class="problem-item">
            <div class="problem-item-icon">🏢</div>
            <div>
              <div class="problem-item-title">Companies waste time on poor fits</div>
              <div class="problem-item-body">Employers consistently report that interns from Ugandan institutions require excessive onboarding. There is no pre-vetting, no readiness standard, and no quality assurance on the student side.</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- SOLUTION -->
<section class="section solution-section">
  <div class="container">
    <div style="text-align:center;margin-bottom:.5rem">
      <div class="s-eyebrow" style="display:inline-block">Our Solution</div>
    </div>
    <h2 class="s-title" style="text-align:center">Two products. One mission.</h2>
    <p class="s-sub" style="text-align:center;margin:0 auto 3.5rem">
      We do not just list internships. We build a pipeline — from discovery to placement to performance.
    </p>
    <div class="solution-grid">
      <div class="solution-card">
        <div class="solution-icon">📋</div>
        <div class="solution-card-label">Product 01</div>
        <h3 class="solution-card-title">The Listings Platform</h3>
        <p class="solution-card-body">A verified, admin-controlled internship board where Ugandan organisations post opportunities and UICT students browse, filter, and apply — from any device, at any time.</p>
        <ul class="solution-list" style="margin-bottom:1.5rem">
          <li>Student registration restricted to @stu.uict.ac.ug</li>
          <li>Admin-reviewed listings before going live</li>
          <li>Search and filter by field, duration, pay type</li>
          <li>Direct email application — no middlemen</li>
        </ul>
        <a href="/internhub/listings.php" class="solution-link">Browse listings →</a>
      </div>

      <div class="solution-card solution-card-featured">
        <div class="solution-icon">🚀</div>
        <div class="solution-card-label">Product 02 · Revenue Driver</div>
        <h3 class="solution-card-title">The Readiness Program</h3>
        <p class="solution-card-body">A structured pre-internship training programme that prepares students for professional environments. UGX 30,000–50,000 per student. This is where the business model lives.</p>
        <ul class="solution-list" style="margin-bottom:1.5rem">
          <li>CV writing and personal branding</li>
          <li>Interview preparation and mock sessions</li>
          <li>Workplace professionalism and ethics</li>
          <li>Field-specific technical refreshers</li>
          <li>Certificate of completion — employer-recognised</li>
        </ul>
        <a href="/internhub/readiness.php" class="solution-link">Learn about the program →</a>
      </div>

      <div class="solution-card">
        <div class="solution-icon">🤝</div>
        <div class="solution-card-label">Product 03</div>
        <h3 class="solution-card-title">Company Partnerships</h3>
        <p class="solution-card-body">Organisations pay to post premium listings, access pre-vetted Readiness-certified students, and build a pipeline of future full-time talent from UICT.</p>
        <ul class="solution-list" style="margin-bottom:1.5rem">
          <li>Standard listing: UGX 150,000</li>
          <li>Featured listing: UGX 300,000</li>
          <li>Access to Readiness-certified students only</li>
          <li>Annual partner packages available</li>
        </ul>
        <a href="/internhub/partners.php" class="solution-link">Partner with us →</a>
      </div>
    </div>
  </div>
</section>

<!-- HOW IT WORKS -->
<section class="section hiw-section">
  <div class="container">
    <div style="text-align:center">
      <div class="s-eyebrow" style="display:inline-block">Process</div>
      <h2 class="s-title">From student to placed intern in 4 steps</h2>
    </div>
    <div class="hiw-steps">
      <div class="hiw-step">
        <div class="hiw-num">01</div>
        <h3 class="hiw-title">Register</h3>
        <p class="hiw-body">Sign up using your official UICT student email. Verified instantly. No manual approval needed.</p>
      </div>
      <div class="hiw-step">
        <div class="hiw-num">02</div>
        <h3 class="hiw-title">Complete Readiness</h3>
        <p class="hiw-body">Enrol in the Readiness Program. Build your CV, sharpen your interview skills, earn your certificate.</p>
      </div>
      <div class="hiw-step">
        <div class="hiw-num">03</div>
        <h3 class="hiw-title">Browse & Apply</h3>
        <p class="hiw-body">Filter internships by your field and preferences. Apply directly to companies with your verified profile.</p>
      </div>
      <div class="hiw-step">
        <div class="hiw-num">04</div>
        <h3 class="hiw-title">Get Placed</h3>
        <p class="hiw-body">Arrive at your internship prepared, certified, and ready to contribute from day one.</p>
      </div>
    </div>
  </div>
</section>

<!-- READINESS TEASER -->
<section class="readiness-section">
  <div class="readiness-inner">
    <div>
      <div class="readiness-eyebrow">🎓 The Readiness Program</div>
      <h2 class="readiness-title">
        We do not just find<br/>
        you an internship.<br/>
        We make you <em>ready</em> for it.
      </h2>
      <p class="readiness-body">
        The Internship Readiness Program is a structured pre-placement training
        course designed specifically for UICT ICT students. Covering everything
        from professional CV writing to field-specific technical preparation —
        it bridges the gap between what university teaches and what industry expects.
      </p>
      <div class="readiness-cta">
        <a href="/internhub/readiness.php" class="btn-gold">
          View the Full Program →
        </a>
        <p class="readiness-note">UGX 30,000 – 50,000 per cohort · Certificate included · Next cohort: Q3 2025</p>
      </div>
    </div>
    <div class="readiness-modules">
      <?php
      $modules=[
        ['📄','CV & Personal Branding','Build a professional CV and LinkedIn profile that stands out to Ugandan employers.'],
        ['🎤','Interview Preparation','Mock interviews, common questions, and how to present yourself confidently.'],
        ['🏢','Workplace Professionalism','Email etiquette, punctuality, communication, and navigating corporate culture.'],
        ['💻','Technical Refresher','Field-specific refreshers in Web Dev, Data, Networking, or Cybersecurity.'],
        ['🏆','Certificate of Completion','A recognised certificate that tells employers you have been vetted and prepared.'],
      ];
      foreach($modules as [$icon,$title,$body]):
      ?>
      <div class="readiness-module">
        <div class="readiness-module-icon"><?php echo $icon; ?></div>
        <div>
          <div class="readiness-module-title"><?php echo $title; ?></div>
          <div class="readiness-module-sub"><?php echo $body; ?></div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- PARTNER CTA -->
<section class="partner-section">
  <div class="container">
    <div class="partner-card">
      <div>
        <div class="partner-eyebrow">For Organisations</div>
        <h2 class="partner-title">Hire smarter.<br/>Build your pipeline.</h2>
        <p class="partner-body">
          Partner with the Digital Internship Portal to access a curated pool of
          verified, Readiness-certified UICT students. Post listings that reach
          the right candidates — not the entire internet.
        </p>
        <div class="partner-actions">
          <a href="/internhub/partners.php" class="btn-primary">View Partnership Options</a>
          <a href="/internhub/auth/register.php?type=company" class="btn-outline">Register Your Organisation</a>
        </div>
      </div>
      <div class="partner-benefits">
        <?php
        $benefits=[
          ['🎯','Targeted reach','Your listing goes directly to UICT CS students — not a general job board with thousands of irrelevant applicants.'],
          ['✅','Pre-vetted candidates','Readiness-certified students have completed professional training. They arrive prepared.'],
          ['📊','Admin-moderated quality','Every listing and application goes through a human review process. No spam, no noise.'],
          ['🤝','Long-term pipeline','Build a relationship with UICT today. Your interns become your future full-time hires.'],
        ];
        foreach($benefits as [$icon,$title,$body]):
        ?>
        <div class="partner-benefit">
          <div class="partner-benefit-icon"><?php echo $icon; ?></div>
          <div>
            <div class="partner-benefit-title"><?php echo $title; ?></div>
            <div class="partner-benefit-body"><?php echo $body; ?></div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>

<!-- TESTIMONIALS -->
<section class="section testimonials-section">
  <div class="container">
    <div style="text-align:center">
      <div class="s-eyebrow" style="display:inline-block">Voices</div>
      <h2 class="s-title">What people are saying</h2>
    </div>
    <div class="testimonials-grid">
      <?php
      $testimonials=[
        ['AK','#0B4D3B','Student 1','Year 2 · Computer Science · UICT',
         'Before this platform I spent weeks going office to office. I found three opportunities in one afternoon and applied the same day. This is exactly what UICT needed.'],
        ['DM','#0D47A1','Student 2','Year 2 · Cybersecurity · UICT',
         'The filter by field is exactly what I needed. Two cybersecurity listings that matched my course content — both within five minutes of signing up.'],
        ['RN','#B45309','Partner 1','HR Manager · Sample 1 Technology',
         'We posted a listing and within a week had five well-prepared applications from UICT students. The quality was genuinely impressive.'],
      ];
      foreach($testimonials as [$init,$col,$name,$role,$text]):
      ?>
      <div class="testimonial">
        <div class="testimonial-stars">★★★★★</div>
        <p class="testimonial-text">"<?php echo $text; ?>"</p>
        <div class="testimonial-author">
          <div class="testimonial-avatar" style="background:<?php echo $col; ?>"><?php echo $init; ?></div>
          <div>
            <div class="testimonial-name"><?php echo $name; ?></div>
            <div class="testimonial-role"><?php echo $role; ?></div>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- FINAL CTA -->
<section class="finalcta-section">
  <div class="finalcta-inner">
    <div class="finalcta-eyebrow">
      <span class="hero-eyebrow-dot"></span>
      Built in Uganda · For Uganda
    </div>
    <h2 class="finalcta-title">
      Your career starts<br/>with the right opportunity.
    </h2>
    <p class="finalcta-body">
      Join the Digital Internship Portal today. Browse verified listings,
      complete the Readiness Program, and walk into your internship prepared
      to make an impact.
    </p>
    <div class="finalcta-actions">
      <a href="/internhub/auth/register.php?type=student" class="btn-solid">
        Register as Student — Free
      </a>
      <a href="/internhub/partners.php" class="btn-ghost-light">
        Partner with Us
      </a>
    </div>
  </div>
</section>

<!-- FOOTER -->
<footer class="footer">
  <div class="footer-top">
    <div>
      <div class="footer-brand-name">Digital Internship Portal</div>
      <p class="footer-brand-desc">Uganda's structured internship platform — connecting UICT students with verified opportunities and preparing them to succeed.</p>
      <div class="footer-badge">
        <span class="footer-badge-dot"></span>
        UICT Nakawa · Kampala, Uganda
      </div>
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
</footer>

</body>
</html>