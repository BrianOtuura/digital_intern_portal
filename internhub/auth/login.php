<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header('Location: /internhub/auth/dashboard.php');
    exit;
}
$error = $_GET['error'] ?? '';
$next  = $_GET['next']  ?? '';
$type  = $_GET['type']  ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Sign In — Digital Internship Portal</title>
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
  --gold:#D4A017;--gold-dk:#A67C00;
  --white:#FFFFFF;--off:#F8FAF9;
  --grey-100:#EEF1EF;--grey-200:#D8DFDB;
  --grey-400:#8A9E96;--grey-600:#4A5E56;--ink:#0D1F19;
  --shadow-sm:0 1px 4px rgba(6,46,34,.07);
  --shadow-md:0 4px 20px rgba(6,46,34,.10);
  --shadow-xl:0 24px 80px rgba(6,46,34,.18);
  --r-sm:6px;--r-md:12px;--r-lg:20px;--r-xl:28px;
  --ease:cubic-bezier(.4,0,.2,1);--t:.2s;
}
html{height:100%}
body{font-family:'Plus Jakarta Sans',sans-serif;background:var(--teal-900);color:var(--ink);min-height:100vh;display:flex;flex-direction:column;-webkit-font-smoothing:antialiased;position:relative;overflow-x:hidden}
body::before{content:'';position:fixed;inset:0;background:radial-gradient(ellipse 70% 60% at 70% 40%,rgba(18,138,105,.35) 0%,transparent 65%),radial-gradient(ellipse 50% 60% at 5% 80%,rgba(11,77,59,.5) 0%,transparent 55%);pointer-events:none;z-index:0}
body::after{content:'';position:fixed;inset:0;background-image:linear-gradient(rgba(255,255,255,.025) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.025) 1px,transparent 1px);background-size:52px 52px;pointer-events:none;z-index:0}
a{text-decoration:none;color:inherit}

/* TOP NAV BAR */
.top-bar{position:relative;z-index:10;padding:1.25rem 2.5rem;display:flex;align-items:center;justify-content:space-between}
.brand{display:flex;align-items:center;gap:9px;font-family:'Fraunces',serif;font-size:1rem;font-weight:700;color:#fff;letter-spacing:-.01em}
.brand-dot{width:7px;height:7px;border-radius:50%;background:var(--teal-400);box-shadow:0 0 0 3px rgba(29,184,133,.2)}
.top-bar-link{font-size:.83rem;color:rgba(255,255,255,.55);font-weight:500;transition:color var(--t) var(--ease)}
.top-bar-link:hover{color:#fff}

/* MAIN WRAPPER */
.auth-wrap{flex:1;display:flex;align-items:center;justify-content:center;padding:2rem 1.5rem 4rem;position:relative;z-index:1}

/* CARD */
.auth-card{width:100%;max-width:440px;background:var(--white);border-radius:var(--r-xl);box-shadow:var(--shadow-xl);overflow:hidden}

/* CARD HEADER */
.card-header{background:var(--teal-800);padding:2rem 2.2rem;position:relative;overflow:hidden}
.card-header::before{content:'';position:absolute;top:-30%;right:-10%;width:180px;height:180px;border-radius:50%;background:radial-gradient(circle,rgba(29,184,133,.2),transparent 70%);pointer-events:none}
.card-header-badge{display:inline-flex;align-items:center;gap:6px;font-size:.68rem;font-weight:600;letter-spacing:.12em;text-transform:uppercase;color:var(--teal-400);background:rgba(29,184,133,.12);border:1px solid rgba(29,184,133,.22);padding:4px 10px;border-radius:99px;margin-bottom:.9rem}
.card-header-title{font-family:'Fraunces',serif;font-size:1.5rem;font-weight:700;color:#fff;line-height:1.15;margin-bottom:.3rem;letter-spacing:-.02em}
.card-header-sub{font-size:.83rem;color:rgba(255,255,255,.55)}

/* CARD BODY */
.card-body{padding:2rem 2.2rem}

/* ROLE TABS */
.role-tabs{display:grid;grid-template-columns:1fr 1fr;border:1.5px solid var(--grey-200);border-radius:var(--r-md);overflow:hidden;margin-bottom:1.8rem}
.role-tab{display:flex;align-items:center;justify-content:center;gap:7px;padding:10px;font-family:'Plus Jakarta Sans',sans-serif;font-size:.85rem;font-weight:600;color:var(--grey-600);background:var(--off);transition:all var(--t) var(--ease);cursor:pointer;border:none;text-decoration:none}
.role-tab:first-child{border-right:1.5px solid var(--grey-200)}
.role-tab.active{background:var(--teal-800);color:#fff}
.role-tab:hover:not(.active){background:var(--grey-100);color:var(--ink)}

/* FIELDS */
.field{display:flex;flex-direction:column;gap:.4rem;margin-bottom:1.1rem}
.field-label{font-size:.78rem;font-weight:600;color:var(--ink)}
.field-input{width:100%;font-family:'Plus Jakarta Sans',sans-serif;font-size:.92rem;color:var(--ink);background:var(--off);border:1.5px solid var(--grey-200);border-radius:var(--r-md);padding:11px 14px;outline:none;transition:border-color var(--t) var(--ease),box-shadow var(--t) var(--ease)}
.field-input:focus{border-color:var(--teal-600);box-shadow:0 0 0 3px var(--teal-50);background:var(--white)}
.field-input::placeholder{color:var(--grey-400)}
.field-hint{font-size:.73rem;color:var(--grey-400);margin-top:2px;line-height:1.5}

/* SUBMIT */
.btn-submit{width:100%;padding:13px;background:var(--teal-800);color:#fff;font-family:'Plus Jakarta Sans',sans-serif;font-weight:700;font-size:.95rem;border:none;border-radius:var(--r-md);cursor:pointer;box-shadow:0 2px 10px rgba(11,77,59,.25);transition:all var(--t) var(--ease);display:flex;align-items:center;justify-content:center;gap:8px;margin-top:.5rem}
.btn-submit:hover{background:var(--teal-900);transform:translateY(-1px)}

/* DIVIDER */
.divider{display:flex;align-items:center;gap:.8rem;margin:1.4rem 0}
.divider-line{flex:1;height:1px;background:var(--grey-100)}
.divider-text{font-size:.75rem;color:var(--grey-400);white-space:nowrap}

/* ALT BUTTONS */
.btn-alt{display:flex;align-items:center;justify-content:center;gap:7px;width:100%;padding:11px;background:var(--off);color:var(--grey-600);font-family:'Plus Jakarta Sans',sans-serif;font-weight:600;font-size:.85rem;border:1.5px solid var(--grey-200);border-radius:var(--r-md);transition:all var(--t) var(--ease);margin-bottom:.6rem;text-decoration:none}
.btn-alt:hover{background:var(--grey-100);border-color:var(--grey-400);color:var(--ink)}
.btn-alt:last-child{margin-bottom:0}

/* ALERT */
.alert-error{background:#FFF1F1;border:1px solid #FECACA;border-radius:var(--r-md);padding:.9rem 1.1rem;color:#B91C1C;font-size:.85rem;margin-bottom:1.3rem;display:flex;align-items:center;gap:.6rem}

/* FOOTER NOTE */
.card-footer{padding:1.2rem 2.2rem;border-top:1px solid var(--grey-100);text-align:center;font-size:.82rem;color:var(--grey-400);background:var(--off)}
.card-footer a{color:var(--teal-700);font-weight:600;transition:color var(--t) var(--ease)}
.card-footer a:hover{color:var(--teal-900)}

/* BOTTOM NOTE */
.auth-bottom{position:relative;z-index:1;text-align:center;padding-bottom:2rem;font-size:.78rem;color:rgba(255,255,255,.25)}
</style>
</head>
<body>

<!-- TOP BAR -->
<div class="top-bar">
  <a class="brand" href="/internhub/index.php">
    <span class="brand-dot"></span>
    Digital Internship Portal
  </a>
  <a href="/internhub/index.php" class="top-bar-link">← Back to home</a>
</div>

<!-- AUTH WRAP -->
<div class="auth-wrap">
  <div class="auth-card">

    <!-- HEADER -->
    <div class="card-header">
      <div class="card-header-badge">🔐 Secure Login</div>
      <div class="card-header-title">Welcome back.</div>
      <div class="card-header-sub">Sign in to access your internship dashboard.</div>
    </div>

    <!-- BODY -->
    <div class="card-body">

      <!-- ROLE TABS -->
      <div class="role-tabs">
        <a href="/internhub/auth/login.php?type=student<?php echo $next ? '&next='.urlencode($next) : ''; ?>"
           class="role-tab <?php echo $type !== 'company' ? 'active' : ''; ?>">
          🎓 Student
        </a>
        <a href="/internhub/auth/login.php?type=company<?php echo $next ? '&next='.urlencode($next) : ''; ?>"
           class="role-tab <?php echo $type === 'company' ? 'active' : ''; ?>">
          🏢 Company
        </a>
      </div>

      <?php if($error): ?>
        <div class="alert-error">
          ⚠️ <?php echo htmlspecialchars($error); ?>
        </div>
      <?php endif; ?>

      <form method="POST" action="/internhub/auth/actions/login_action.php">
        <input type="hidden" name="next" value="<?php echo htmlspecialchars($next); ?>"/>

        <div class="field">
          <label class="field-label">Email Address</label>
          <input class="field-input" type="email" name="email"
            placeholder="<?php echo $type === 'company' ? 'hr@company.co.ug' : '2401901918@stu.uict.ac.ug'; ?>"
            required autofocus/>
          <?php if($type !== 'company'): ?>
            <div class="field-hint">Use your UICT institutional email (@stu.uict.ac.ug)</div>
          <?php endif; ?>
        </div>

        <div class="field" style="margin-bottom:1.5rem">
          <label class="field-label">Password</label>
          <input class="field-input" type="password" name="password" placeholder="••••••••" required/>
        </div>

        <button type="submit" class="btn-submit">
          Sign In
          <svg width="15" height="15" viewBox="0 0 15 15" fill="none"><path d="M3 7.5h9M9 4l3.5 3.5L9 11" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </button>
      </form>

      <div class="divider">
        <div class="divider-line"></div>
        <span class="divider-text">Don't have an account?</span>
        <div class="divider-line"></div>
      </div>

      <a href="/internhub/auth/register.php?type=student" class="btn-alt">🎓 Register as Student</a>
      <a href="/internhub/auth/register.php?type=company" class="btn-alt">🏢 Register as Company</a>

    </div>

    <!-- CARD FOOTER -->
    <div class="card-footer">
      Trouble signing in? <a href="/internhub/contact.php">Contact the team</a>
    </div>

  </div>
</div>

<div class="auth-bottom">
  © 2025 Digital Internship Portal · UICT Nakawa, Kampala
</div>

</body>
</html>
