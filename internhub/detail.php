<?php
session_start();
require_once 'includes/db.php';

function getInternshipStatus($deadline) {
    $today = new DateTime();
    $deadlineDate = new DateTime($deadline);
    
    if ($deadlineDate < $today) {
        return ['text' => 'Closed', 'class' => 'b-closed', 'icon' => '🔒'];
    }
    
    $daysLeft = $today->diff($deadlineDate)->days;
    
    if ($daysLeft <= 7) {
        return ['text' => 'Closing Soon', 'class' => 'b-closing', 'icon' => '⚠️'];
    }
    
    return ['text' => 'Open', 'class' => 'b-open', 'icon' => '✅'];
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) { header('Location: listings.php'); exit; }

$stmt = $pdo->prepare("SELECT * FROM internships WHERE id = :id AND status = 'approved' LIMIT 1");
$stmt->execute([':id' => $id]);
$internship = $stmt->fetch();
if (!$internship) { header('Location: listings.php'); exit; }

$status = getInternshipStatus($internship['deadline']);

// Handle Application Submission with file upload
$apply_message = '';
$apply_success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['apply'])) {
    if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'student') {
        $apply_message = '<div class="alert alert-warning">🔐 Please <a href="/internhub/auth/login.php">sign in</a> as a student to apply.</div>';
    } else {
        $student_id = $_SESSION['user_id'];
        $cover_letter = trim($_POST['cover_letter'] ?? '');
        $portfolio_link = trim($_POST['portfolio_link'] ?? '');
        
        // Handle CV file upload
        $cv_path = null;
        if (isset($_FILES['cv_file']) && $_FILES['cv_file']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = 'uploads/cvs/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            $file_ext = pathinfo($_FILES['cv_file']['name'], PATHINFO_EXTENSION);
            $file_name = $student_id . '_' . time() . '.' . $file_ext;
            $cv_path = $upload_dir . $file_name;
            move_uploaded_file($_FILES['cv_file']['tmp_name'], $cv_path);
        }
        
        // Check if already applied
        $check = $pdo->prepare("SELECT id FROM applications WHERE student_id = ? AND listing_id = ?");
        $check->execute([$student_id, $id]);
        
        if ($check->rowCount() > 0) {
            $apply_message = '<div class="alert alert-info">📋 You have already applied for this internship.</div>';
        } else {
            $insert = $pdo->prepare("INSERT INTO applications (student_id, listing_id, cover_letter, cv_path, portfolio_link, status, created_at) VALUES (?, ?, ?, ?, ?, 'pending', NOW())");
            if ($insert->execute([$student_id, $id, $cover_letter, $cv_path, $portfolio_link])) {
                $apply_success = true;
                $apply_message = '<div class="alert alert-success">
                    <strong>✅ Application Submitted Successfully!</strong><br>
                    Your CV and cover letter have been uploaded.<br>
                    You can track your application in <a href="/internhub/my-applications.php">My Applications</a>.<br>
                    The company will contact you directly if shortlisted.
                </div>';
            } else {
                $apply_message = '<div class="alert alert-error">❌ Error submitting application. Please try again.</div>';
            }
        }
    }
}

$responsibilities = array_filter(array_map('trim', explode('|', $internship['responsibilities'] ?? '')));
$requirements = array_filter(array_map('trim', explode('|', $internship['requirements'] ?? '')));
$tags = array_filter(array_map('trim', explode(',', $internship['tags'] ?? '')));
$deadline = new DateTime($internship['deadline']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover"/>
<title><?php echo htmlspecialchars($internship['title']); ?> — Digital Internship Portal</title>
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
body{font-family:'Plus Jakarta Sans',sans-serif;background:var(--off);color:var(--ink);-webkit-font-smoothing:antialiased}
a{text-decoration:none;color:inherit}

/* Navigation */
.nav{position:sticky;top:0;z-index:200;height:68px;display:flex;align-items:center;justify-content:space-between;padding:0 2.5rem;background:rgba(255,255,255,.95);backdrop-filter:blur(14px);border-bottom:1px solid var(--grey-100)}
.nav-brand{display:flex;align-items:center;gap:10px;font-family:'Fraunces',serif;font-size:1.1rem;font-weight:700;color:var(--teal-800)}
.nav-brand-dot{width:8px;height:8px;border-radius:50%;background:var(--teal-600);box-shadow:0 0 0 3px var(--teal-100)}
.nav-links{display:flex;align-items:center;gap:4px}
.nav-links a{font-size:.875rem;font-weight:500;color:var(--grey-600);padding:6px 13px;border-radius:var(--r-sm);transition:all var(--t) var(--ease)}
.nav-links a:hover{color:var(--ink);background:var(--off)}
.nav-links a.active{color:var(--teal-700);font-weight:600}
.nav-cta{background:var(--teal-800)!important;color:#fff!important;border-radius:var(--r-sm)!important;padding:8px 18px!important;box-shadow:0 2px 8px rgba(11,77,59,.22)!important}
.nav-cta:hover{background:var(--teal-900)!important;transform:translateY(-1px)!important}

/* Container */
.container{max-width:1000px;margin:0 auto;padding:2rem 2rem}

/* Back button */
.back-link{display:inline-flex;align-items:center;gap:6px;margin-bottom:1.5rem;color:var(--grey-600);font-size:.85rem;transition:color var(--t) var(--ease)}
.back-link:hover{color:var(--teal-700)}

/* Hero Section */
.hero{background:var(--white);border-radius:var(--r-xl);border:1px solid var(--grey-100);overflow:hidden;margin-bottom:2rem}
.hero-header{background:var(--teal-800);padding:2rem 2rem;position:relative}
.hero-header::before{content:'';position:absolute;top:-30%;right:-5%;width:200px;height:200px;border-radius:50%;background:radial-gradient(circle,rgba(29,184,133,.15),transparent 70%);pointer-events:none}
.hero-company{display:flex;align-items:center;gap:.8rem;margin-bottom:1rem;position:relative;z-index:1}
.hero-logo{width:56px;height:56px;border-radius:var(--r-md);display:flex;align-items:center;justify-content:center;font-family:'Fraunces',serif;font-weight:700;font-size:1.1rem;color:#fff;box-shadow:var(--shadow-sm)}
.hero-company-name{font-size:.9rem;font-weight:500;color:rgba(255,255,255,.7)}
.hero-title{font-family:'Fraunces',serif;font-size:clamp(1.5rem,3vw,2rem);font-weight:700;color:#fff;margin-bottom:.5rem;position:relative;z-index:1}
.hero-meta{display:flex;flex-wrap:wrap;gap:1rem;margin-top:1rem;position:relative;z-index:1}
.hero-meta-item{display:flex;align-items:center;gap:.4rem;font-size:.8rem;color:rgba(255,255,255,.6)}
.hero-meta-item span:first-child{font-size:.9rem}
.status-badge{display:inline-flex;align-items:center;gap:6px;padding:5px 12px;border-radius:99px;font-size:.75rem;font-weight:600}
.b-closed{background:#FEF2F2;color:#DC2626;border:1px solid #FECACA}
.b-closing{background:#FFFBEB;color:#B45309;border:1px solid #FDE68A}
.b-open{background:#F0FDF4;color:#16A34A;border:1px solid #BBF7D0}

/* Content Sections */
.content-section{background:var(--white);border-radius:var(--r-xl);border:1px solid var(--grey-100);margin-bottom:2rem;overflow:hidden}
.section-header{padding:1.2rem 2rem;border-bottom:1px solid var(--grey-100);display:flex;align-items:center;gap:.6rem}
.section-header h2{font-family:'Fraunces',serif;font-size:1.1rem;font-weight:600;color:var(--ink)}
.section-body{padding:1.5rem 2rem}
.section-body p{font-size:.9rem;color:var(--grey-600);line-height:1.7;margin-bottom:1rem}
.section-body p:last-child{margin-bottom:0}
.list-items{list-style:none;padding:0}
.list-items li{display:flex;align-items:flex-start;gap:.8rem;padding:.6rem 0;border-bottom:1px solid var(--grey-100);font-size:.88rem;color:var(--grey-600);line-height:1.6}
.list-items li:last-child{border-bottom:none}
.list-items li::before{content:'✓';color:var(--teal-600);font-weight:700;flex-shrink:0}
.tags{display:flex;flex-wrap:wrap;gap:.5rem;margin-top:1rem}
.tag{background:var(--off);border:1px solid var(--grey-200);padding:4px 12px;border-radius:99px;font-size:.7rem;font-weight:500;color:var(--grey-600)}

/* Apply Card */
.apply-card{background:linear-gradient(135deg,var(--teal-800),var(--teal-900));border-radius:var(--r-xl);overflow:hidden;margin-bottom:2rem}
.apply-card-inner{padding:2rem;text-align:center}
.apply-eyebrow{font-size:.7rem;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:var(--teal-400);margin-bottom:.5rem}
.apply-title{font-family:'Fraunces',serif;font-size:1.3rem;font-weight:700;color:#fff;margin-bottom:1rem}
.field{margin-bottom:1rem;text-align:left}
.field label{display:block;font-size:.8rem;font-weight:600;color:rgba(255,255,255,.8);margin-bottom:.4rem}
.field-input, .field-textarea{width:100%;padding:12px 16px;font-family:inherit;font-size:.9rem;border:1.5px solid rgba(255,255,255,.2);border-radius:var(--r-md);background:rgba(255,255,255,.08);color:#fff;resize:vertical}
.field-input::placeholder, .field-textarea::placeholder{color:rgba(255,255,255,.4)}
.field-input:focus, .field-textarea:focus{outline:none;border-color:var(--teal-400);background:rgba(255,255,255,.12)}
.field small{display:block;font-size:.65rem;color:rgba(255,255,255,.4);margin-top:4px}
.btn-apply{display:inline-flex;align-items:center;justify-content:center;gap:8px;background:var(--gold);color:var(--ink);font-weight:700;font-size:.9rem;padding:12px 28px;border-radius:var(--r-sm);border:none;cursor:pointer;transition:all var(--t) var(--ease);width:100%;max-width:300px;margin:0 auto}
.btn-apply:hover{background:var(--gold-dk);transform:translateY(-2px)}
.btn-apply-disabled{background:var(--grey-400);cursor:not-allowed;opacity:.6}
.apply-note{font-size:.7rem;color:rgba(255,255,255,.45);margin-top:1rem}
.alert{padding:1rem;border-radius:var(--r-md);margin-bottom:1rem;font-size:.85rem;text-align:left}
.alert-success{background:rgba(34,197,94,.15);border:1px solid rgba(34,197,94,.3);color:#22C55E}
.alert-info{background:rgba(29,184,133,.1);border:1px solid rgba(29,184,133,.2);color:var(--teal-400)}
.alert-error{background:rgba(248,113,113,.1);border:1px solid rgba(248,113,113,.2);color:#F87171}
.alert-warning{background:rgba(212,160,23,.1);border:1px solid rgba(212,160,23,.2);color:var(--gold)}
.alert a{color:inherit;text-decoration:underline}

/* Footer */
.footer{background:#040F0A;padding:3rem 0 2rem;margin-top:2rem}
.footer-inner{max-width:1000px;margin:0 auto;padding:0 2rem;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem;font-size:.78rem;color:rgba(255,255,255,.22)}
.footer-brand{font-family:'Fraunces',serif;font-size:.9rem;font-weight:700;color:rgba(255,255,255,.5)}

@media(max-width:768px){
  .container{padding:1rem}
  .hero-header{padding:1.5rem}
  .section-header,.section-body{padding:1rem}
  .apply-card-inner{padding:1.5rem}
}
</style>
</head>
<body>

<nav class="nav">
  <a class="nav-brand" href="/internhub/index.php">
    <span class="nav-brand-dot"></span>
    Digital Internship Portal
  </a>
  <div class="nav-links">
    <a href="/internhub/index.php">Home</a>
    <a href="/internhub/listings.php" class="active">Internships</a>
    <a href="/internhub/readiness.php">Readiness Program</a>
    <a href="/internhub/partners.php">For Companies</a>
    <a href="/internhub/about.php">About</a>
    <?php if(isset($_SESSION['user_id'])): ?>
      <a href="/internhub/auth/dashboard.php">Dashboard</a>
      <a href="/internhub/auth/actions/logout.php" class="nav-cta">Log Out</a>
    <?php else: ?>
      <a href="/internhub/auth/login.php">Sign In</a>
      <a href="/internhub/auth/register.php" class="nav-cta">Get Started</a>
    <?php endif; ?>
  </div>
</nav>

<div class="container">
  <a href="/internhub/listings.php" class="back-link">
    ← Back to all internships
  </a>

  <!-- HERO SECTION - Internship Details First -->
  <div class="hero">
    <div class="hero-header">
      <div class="hero-company">
        <div class="hero-logo" style="background:<?php echo htmlspecialchars($internship['logo_color']); ?>">
          <?php echo htmlspecialchars($internship['logo']); ?>
        </div>
        <div class="hero-company-name"><?php echo htmlspecialchars($internship['company']); ?></div>
      </div>
      <h1 class="hero-title"><?php echo htmlspecialchars($internship['title']); ?></h1>
      <div class="hero-meta">
        <div class="hero-meta-item"><span>📍</span> <?php echo htmlspecialchars($internship['location']); ?></div>
        <div class="hero-meta-item"><span>🗂️</span> <?php echo htmlspecialchars($internship['field']); ?></div>
        <div class="hero-meta-item"><span>⏱️</span> <?php echo $internship['duration']; ?> months</div>
        <div class="hero-meta-item"><span>💰</span> <?php echo htmlspecialchars($internship['stipend']); ?></div>
        <div class="hero-meta-item"><span>📅</span> Deadline: <?php echo $deadline->format('d M Y'); ?></div>
        <div><span class="status-badge <?php echo $status['class']; ?>"><?php echo $status['icon']; ?> <?php echo $status['text']; ?></span></div>
      </div>
    </div>
  </div>

  <!-- DESCRIPTION SECTION -->
  <div class="content-section">
    <div class="section-header">
      <span>📋</span>
      <h2>About the Internship</h2>
    </div>
    <div class="section-body">
      <p><?php echo nl2br(htmlspecialchars($internship['description'])); ?></p>
      <?php if(!empty($tags)): ?>
        <div class="tags">
          <?php foreach($tags as $tag): ?>
            <span class="tag">#<?php echo htmlspecialchars($tag); ?></span>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <!-- RESPONSIBILITIES SECTION -->
  <?php if(!empty($responsibilities)): ?>
  <div class="content-section">
    <div class="section-header">
      <span>⚡</span>
      <h2>Key Responsibilities</h2>
    </div>
    <div class="section-body">
      <ul class="list-items">
        <?php foreach($responsibilities as $resp): ?>
          <li><?php echo htmlspecialchars($resp); ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  </div>
  <?php endif; ?>

  <!-- REQUIREMENTS SECTION -->
  <?php if(!empty($requirements)): ?>
  <div class="content-section">
    <div class="section-header">
      <span>📜</span>
      <h2>Requirements</h2>
    </div>
    <div class="section-body">
      <ul class="list-items">
        <?php foreach($requirements as $req): ?>
          <li><?php echo htmlspecialchars($req); ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  </div>
  <?php endif; ?>

  <!-- ADDITIONAL INFO -->
  <div class="content-section">
    <div class="section-header">
      <span>ℹ️</span>
      <h2>Additional Information</h2>
    </div>
    <div class="section-body">
      <p><strong>🕒 Duration:</strong> <?php echo $internship['duration']; ?> months</p>
      <p><strong>👥 Available Slots:</strong> <?php echo $internship['slots']; ?></p>
      <p><strong>📞 Contact:</strong> <?php echo htmlspecialchars($internship['contact']); ?></p>
    </div>
  </div>

  <!-- APPLICATION FORM - WITH CV UPLOAD AND PORTFOLIO LINK -->
  <div class="apply-card">
    <div class="apply-card-inner">
      <div class="apply-eyebrow">
        <?php echo $deadline < new DateTime() ? '⛔ Applications Closed' : '✅ Applications Open'; ?>
      </div>
      <div class="apply-title">
        <?php echo $deadline < new DateTime() ? 'This listing has closed.' : 'Apply for this Internship'; ?>
      </div>

      <?php echo $apply_message; ?>

      <?php if($deadline >= new DateTime() && isset($_SESSION['user_id']) && $_SESSION['user_role'] === 'student' && !$apply_success): ?>
        <form method="POST" enctype="multipart/form-data">
          <div class="field">
            <label>📄 Cover Letter</label>
            <textarea name="cover_letter" class="field-textarea" rows="5" 
              placeholder="Dear Hiring Manager,

I am a [Year] student at UICT studying [Course]. I am interested in this internship because..."></textarea>
          </div>
          
          <div class="field">
            <label>📎 Upload CV (PDF or DOC)</label>
            <input type="file" name="cv_file" accept=".pdf,.doc,.docx" class="field-input">
            <small>Max 2MB. PDF or Word document only.</small>
          </div>
          
          <div class="field">
            <label>🔗 Portfolio/GitHub Link (Optional)</label>
            <input type="url" name="portfolio_link" class="field-input" placeholder="https://github.com/yourusername">
          </div>
          
          <button type="submit" name="apply" class="btn-apply">
            Submit Application
            <svg width="14" height="14" viewBox="0 0 14 14" fill="none"><path d="M2.5 7h9M8 3.5L11.5 7 8 10.5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
          </button>
        </form>
        <div class="apply-note">
          📋 Your CV and cover letter will be shared with the employer.
        </div>

      <?php elseif($deadline >= new DateTime() && (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'student')): ?>
        <a href="/internhub/auth/login.php?next=<?php echo urlencode('/internhub/detail.php?id='.$id); ?>" class="btn-apply">
          Sign in as Student to Apply
        </a>
      <?php elseif($deadline >= new DateTime() && $apply_success): ?>
        <div style="text-align:center">
          <a href="/internhub/listings.php" class="btn-apply" style="background:var(--teal-600);max-width:250px">
            Browse More Internships →
          </a>
        </div>
      <?php else: ?>
        <span class="btn-apply btn-apply-disabled">Applications Closed</span>
        <div class="apply-note">Check back for new opportunities from other employers</div>
      <?php endif; ?>
    </div>
  </div>
</div>

<footer class="footer">
  <div class="footer-inner">
    <span class="footer-brand">Digital Internship Portal</span>
    <span>© 2025 · UICT Nakawa · Built by Otuura Brian Oneka & Team</span>
  </div>
</footer>

</body>
</html>