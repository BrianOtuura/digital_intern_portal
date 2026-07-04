<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header('Location: /internhub/auth/dashboard.php');
    exit;
}
$error   = $_GET['error']   ?? '';
$success = $_GET['success'] ?? '';
$type    = $_GET['type']    ?? 'student';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Create Account — Digital Internship Portal</title>
<link rel="preconnect" href="https://fonts.googleapis.com"/>
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,300;0,9..144,600;0,9..144,700;1,9..144,300&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
<link rel="stylesheet" href="/internhub/css/style.css">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{
  --teal-900:#062E22;--teal-800:#0B4D3B;--teal-700:#0E6B52;
  --teal-600:#128A69;--teal-400:#1DB885;--teal-100:#C5F0E3;--teal-50:#EDF9F5;
  --gold:#D4A017;--gold-dk:#A67C00;
  --white:#FFFFFF;--off:#F8FAF9;
  --grey-100:#EEF1EF;--grey-200:#D8DFDB;--grey-400:#8A9E96;--grey-600:#4A5E56;--ink:#0D1F19;
  --shadow-xl:0 24px 80px rgba(6,46,34,.2);
  --r-sm:6px;--r-md:12px;--r-lg:20px;--r-xl:28px;
  --ease:cubic-bezier(.4,0,.2,1);--t:.2s;
}
html{height:100%}
body{font-family:'Plus Jakarta Sans',sans-serif;background:var(--teal-900);color:var(--ink);min-height:100vh;display:flex;flex-direction:column;-webkit-font-smoothing:antialiased;position:relative;overflow-x:hidden}
body::before{content:'';position:fixed;inset:0;background:radial-gradient(ellipse 70% 60% at 70% 40%,rgba(18,138,105,.35) 0%,transparent 65%),radial-gradient(ellipse 50% 60% at 5% 80%,rgba(11,77,59,.5) 0%,transparent 55%);pointer-events:none;z-index:0}
body::after{content:'';position:fixed;inset:0;background-image:linear-gradient(rgba(255,255,255,.025) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.025) 1px,transparent 1px);background-size:52px 52px;pointer-events:none;z-index:0}
a{text-decoration:none;color:inherit}

.top-bar{position:relative;z-index:10;padding:1.25rem 2.5rem;display:flex;align-items:center;justify-content:space-between}
.brand{display:flex;align-items:center;gap:9px;font-family:'Fraunces',serif;font-size:1rem;font-weight:700;color:#fff;letter-spacing:-.01em}
.brand-dot{width:7px;height:7px;border-radius:50%;background:var(--teal-400);box-shadow:0 0 0 3px rgba(29,184,133,.2)}
.top-bar-link{font-size:.83rem;color:rgba(255,255,255,.55);font-weight:500;transition:color var(--t) var(--ease)}
.top-bar-link:hover{color:#fff}

.auth-wrap{flex:1;display:flex;align-items:center;justify-content:center;padding:1.5rem 1.5rem 3rem;position:relative;z-index:1}
.auth-card{width:100%;max-width:520px;background:var(--white);border-radius:var(--r-xl);box-shadow:var(--shadow-xl);overflow:hidden}

.card-header{background:var(--teal-800);padding:1.8rem 2.2rem;position:relative;overflow:hidden}
.card-header::before{content:'';position:absolute;top:-30%;right:-10%;width:180px;height:180px;border-radius:50%;background:radial-gradient(circle,rgba(29,184,133,.2),transparent 70%);pointer-events:none}
.card-header-badge{display:inline-flex;align-items:center;gap:6px;font-size:.68rem;font-weight:600;letter-spacing:.12em;text-transform:uppercase;color:var(--teal-400);background:rgba(29,184,133,.12);border:1px solid rgba(29,184,133,.22);padding:4px 10px;border-radius:99px;margin-bottom:.8rem}
.card-header-title{font-family:'Fraunces',serif;font-size:1.4rem;font-weight:700;color:#fff;line-height:1.15;margin-bottom:.25rem;letter-spacing:-.02em}
.card-header-sub{font-size:.82rem;color:rgba(255,255,255,.55)}

.card-body{padding:1.8rem 2.2rem}

.role-tabs{display:grid;grid-template-columns:1fr 1fr;border:1.5px solid var(--grey-200);border-radius:var(--r-md);overflow:hidden;margin-bottom:1.6rem}
.role-tab{display:flex;align-items:center;justify-content:center;gap:7px;padding:10px;font-family:'Plus Jakarta Sans',sans-serif;font-size:.85rem;font-weight:600;color:var(--grey-600);background:var(--off);transition:all var(--t) var(--ease);text-decoration:none}
.role-tab:first-child{border-right:1.5px solid var(--grey-200)}
.role-tab.active{background:var(--teal-800);color:#fff}
.role-tab:hover:not(.active){background:var(--grey-100);color:var(--ink)}

.alert{border-radius:var(--r-md);padding:.85rem 1.1rem;margin-bottom:1.2rem;font-size:.85rem;display:flex;align-items:flex-start;gap:.6rem}
.alert-error{background:#FFF1F1;border:1px solid #FECACA;color:#B91C1C}
.alert-success{background:var(--teal-50);border:1px solid var(--teal-100);color:var(--teal-700)}

.field-row{display:grid;grid-template-columns:1fr 1fr;gap:.9rem;margin-bottom:1rem}
.field{display:flex;flex-direction:column;gap:.4rem;margin-bottom:1rem}
.field:last-of-type{margin-bottom:0}
.field-label{font-size:.775rem;font-weight:600;color:var(--ink)}
.field-label span{color:var(--teal-600)}
.field-input,.field-select{width:100%;font-family:'Plus Jakarta Sans',sans-serif;font-size:.9rem;color:var(--ink);background:var(--off);border:1.5px solid var(--grey-200);border-radius:var(--r-md);padding:10px 13px;outline:none;transition:border-color var(--t) var(--ease),box-shadow var(--t) var(--ease)}
.field-input:focus,.field-select:focus{border-color:var(--teal-600);box-shadow:0 0 0 3px var(--teal-50);background:var(--white)}
.field-input::placeholder{color:var(--grey-400)}
.field-select{cursor:pointer;appearance:none;background-image:url("data:image/svg+xml,%3Csvg width='12' height='8' viewBox='0 0 12 8' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1 1L6 7L11 1' stroke='%238A9E96' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 13px center;padding-right:34px}
.field-hint{font-size:.72rem;color:var(--grey-400);margin-top:2px;line-height:1.5}

.section-divider{display:flex;align-items:center;gap:.75rem;margin:1.2rem 0 1rem}
.section-divider-line{flex:1;height:1px;background:var(--grey-100)}
.section-divider-text{font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--grey-400);white-space:nowrap}

.btn-submit{width:100%;padding:13px;background:var(--teal-800);color:#fff;font-family:'Plus Jakarta Sans',sans-serif;font-weight:700;font-size:.95rem;border:none;border-radius:var(--r-md);cursor:pointer;box-shadow:0 2px 10px rgba(11,77,59,.25);transition:all var(--t) var(--ease);display:flex;align-items:center;justify-content:center;gap:8px;margin-top:1.4rem}
.btn-submit:hover{background:var(--teal-900);transform:translateY(-1px)}

.card-footer{padding:1.1rem 2.2rem;border-top:1px solid var(--grey-100);text-align:center;font-size:.82rem;color:var(--grey-400);background:var(--off)}
.card-footer a{color:var(--teal-700);font-weight:600}
.card-footer a:hover{color:var(--teal-900)}

.auth-bottom{position:relative;z-index:1;text-align:center;padding-bottom:1.5rem;font-size:.78rem;color:rgba(255,255,255,.25)}

/* Student-only / company-only toggling */
.student-fields{display:<?php echo $type==='company'?'none':'block'; ?>}
.company-fields{display:<?php echo $type==='company'?'block':'none'; ?>}
</style>
</head>
<body>

<div class="top-bar">
  <a class="brand" href="/internhub/index.php">
    <span class="brand-dot"></span>
    Digital Internship Portal
  </a>
  <a href="/internhub/auth/login.php" class="top-bar-link">Already registered? Sign in →</a>
</div>

<div class="auth-wrap">
  <div class="auth-card">

    <div class="card-header">
      <div class="card-header-badge">✨ Create Account</div>
      <div class="card-header-title">Join the platform.</div>
      <div class="card-header-sub">Free for students · Companies post from UGX 150,000</div>
    </div>

    <div class="card-body">

      <!-- ROLE TABS -->
      <div class="role-tabs">
        <a href="/internhub/auth/register.php?type=student"
           class="role-tab <?php echo $type!=='company'?'active':''; ?>">🎓 I'm a Student</a>
        <a href="/internhub/auth/register.php?type=company"
           class="role-tab <?php echo $type==='company'?'active':''; ?>">🏢 I'm a Company</a>
      </div>

      <?php if($error): ?>
        <div class="alert alert-error">⚠️ <?php echo htmlspecialchars($error); ?></div>
      <?php endif; ?>
      <?php if($success): ?>
        <div class="alert alert-success">✅ <?php echo htmlspecialchars($success); ?></div>
      <?php endif; ?>

      <form method="POST" action="/internhub/auth/actions/register_action.php">
        <input type="hidden" name="role" value="<?php echo htmlspecialchars($type); ?>"/>

        <!-- SHARED: Name + Email -->
        <div class="field-row">
          <div class="field">
            <label class="field-label">Full Name <span>*</span></label>
            <input class="field-input" type="text" name="full_name"
              placeholder="<?php echo $type==='company'?'Contact person name':'e.g. Brian Oneka'; ?>" required/>
          </div>
          <div class="field">
            <label class="field-label">Email Address <span>*</span></label>
            <input class="field-input" type="email" name="email"
              placeholder="<?php echo $type==='company'?'hr@company.co.ug':'2401901918@stu.uict.ac.ug'; ?>" required/>
          </div>
        </div>
        <?php if($type!=='company'): ?>
          <div class="field-hint" style="margin-top:-0.5rem;margin-bottom:1rem">
            ⚠️ You must use your official UICT email ending in <strong>@stu.uict.ac.ug</strong>
          </div>
        <?php endif; ?>

        <!-- STUDENT FIELDS -->
        <div class="student-fields">
          <div class="section-divider">
            <div class="section-divider-line"></div>
            <span class="section-divider-text">Academic Details</span>
            <div class="section-divider-line"></div>
          </div>
          <div class="field-row">
            <div class="field">
              <label class="field-label">Student ID <span>*</span></label>
              <input class="field-input" type="text" name="student_id" placeholder="e.g. 2401901918"/>
            </div>
            <div class="field">
              <label class="field-label">Year of Study</label>
              <select class="field-select" name="study_year">
                <option value="Year 1">Year 1</option>
                <option value="Year 2">Year 2</option>
                <option value="Year 3">Year 3</option>
              </select>
            </div>
          </div>
          <div class="field">
            <label class="field-label">Course / Programme</label>
            <select class="field-select" name="course">
              <option>Computer Science</option>
              <option>Information Technology</option>
              <option>Software Engineering</option>
              <option>Cybersecurity</option>
              <option>Networking &amp; Communications</option>
              <option>Data Science</option>
            </select>
          </div>
        </div>

        <!-- COMPANY FIELDS -->
        <div class="company-fields">
          <div class="section-divider">
            <div class="section-divider-line"></div>
            <span class="section-divider-text">Organisation Details</span>
            <div class="section-divider-line"></div>
          </div>
          <div class="field">
            <label class="field-label">Company / Organisation Name <span>*</span></label>
            <input class="field-input" type="text" name="company_name" placeholder="e.g. MTN Uganda"/>
          </div>
          <div class="field-row">
            <div class="field">
              <label class="field-label">Industry</label>
              <select class="field-select" name="industry">
                <option>Telecommunications</option>
                <option>Banking &amp; Finance</option>
                <option>Government / Public Sector</option>
                <option>NGO / Non-Profit</option>
                <option>Software / Tech</option>
                <option>Media &amp; Communications</option>
                <option>Healthcare</option>
                <option>Other</option>
              </select>
            </div>
            <div class="field">
              <label class="field-label">Phone / WhatsApp</label>
              <input class="field-input" type="text" name="phone" placeholder="+256 700 000 000"/>
            </div>
          </div>
          <div class="field">
            <label class="field-label">Physical Address / Location</label>
            <input class="field-input" type="text" name="address" placeholder="e.g. Kampala Road, Kampala"/>
          </div>
        </div>

        <!-- SHARED: Password -->
        <div class="section-divider">
          <div class="section-divider-line"></div>
          <span class="section-divider-text">Set Password</span>
          <div class="section-divider-line"></div>
        </div>
        <div class="field-row">
          <div class="field">
            <label class="field-label">Password <span>*</span></label>
            <input class="field-input" type="password" name="password" placeholder="Min. 8 characters" required/>
          </div>
          <div class="field">
            <label class="field-label">Confirm Password <span>*</span></label>
            <input class="field-input" type="password" name="confirm_password" placeholder="Repeat password" required/>
          </div>
        </div>

        <button type="submit" class="btn-submit">
          Create Account
          <svg width="15" height="15" viewBox="0 0 15 15" fill="none"><path d="M3 7.5h9M9 4l3.5 3.5L9 11" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </button>
      </form>
    </div>

    <div class="card-footer">
      Already have an account? <a href="/internhub/auth/login.php">Sign in here</a>
    </div>
  </div>
</div>

<div class="auth-bottom">© 2025 Digital Internship Portal · UICT Nakawa, Kampala</div>

</body>
</html>
