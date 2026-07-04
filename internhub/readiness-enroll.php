<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'student') {
    header('Location: /internhub/auth/login.php');
    exit;
}
require_once 'includes/db.php';

$userId = $_SESSION['user_id'];
$error = '';
$success = false;

// Check if already certified
$check = $pdo->prepare("SELECT readiness_certified FROM users WHERE id = ?");
$check->execute([$userId]);
$user = $check->fetch();

if ($user && $user['readiness_certified'] == 1) {
    header('Location: /internhub/readiness.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tier = $_POST['tier'] ?? 'foundation';
    $payment_ref = trim($_POST['payment_ref'] ?? '');
    
    if (empty($payment_ref)) {
        $error = "Enter payment reference";
    } else {
        $cert_code = 'DIP-' . strtoupper(substr(uniqid(), -6));
        $now = date('Y-m-d H:i:s');
        
        // Delete old enrollments
        $pdo->prepare("DELETE FROM readiness_enrollments WHERE user_id = ?")->execute([$userId]);
        
        // Insert new
        $insert = $pdo->prepare("INSERT INTO readiness_enrollments (user_id, tier, status, payment_ref, certificate_code, completed_at, created_at) VALUES (?, ?, 'completed', ?, ?, ?, NOW())");
        $insert->execute([$userId, $tier, $payment_ref, $cert_code, $now]);
        
        // Update user
        $update = $pdo->prepare("UPDATE users SET readiness_certified = 1, readiness_tier = ?, readiness_certificate_code = ? WHERE id = ?");
        $update->execute([$tier, $cert_code, $userId]);
        
        $success = true;
        header('refresh:2;url=/internhub/readiness.php');
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Enroll - Readiness Program</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/internhub/css/style.css">
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:'Plus Jakarta Sans',sans-serif;background:#062E22;min-height:100vh;display:flex;align-items:center;justify-content:center}
.card{background:#fff;max-width:500px;width:90%;border-radius:28px;padding:2rem;margin:2rem auto}
h1{font-size:1.5rem;margin-bottom:.5rem}
.error{background:#FEF2F2;border:1px solid #FECACA;padding:12px;border-radius:12px;color:#DC2626;margin-bottom:1rem}
.success{background:#EDF9F5;border:1px solid #C5F0E3;padding:12px;border-radius:12px;color:#128A69;margin-bottom:1rem;text-align:center}
.option{border:2px solid #EEF1EF;border-radius:16px;padding:1rem;margin-bottom:1rem;cursor:pointer}
.option.selected{border-color:#128A69;background:#EDF9F5}
.option-header{display:flex;justify-content:space-between;margin-bottom:.3rem}
.option-name{font-weight:700}
.option-price{font-weight:700;color:#128A69}
.option-desc{font-size:.75rem;color:#8A9E96}
.field{margin:1.5rem 0}
label{font-weight:600;font-size:.8rem;display:block;margin-bottom:.5rem}
input{width:100%;padding:12px;border:1.5px solid #D8DFDB;border-radius:12px;font-size:.9rem}
input:focus{outline:none;border-color:#128A69}
button{width:100%;padding:14px;background:#0B4D3B;color:#fff;font-weight:700;font-size:1rem;border:none;border-radius:12px;cursor:pointer}
button:hover{background:#062E22}
.demo-note{background:#FBF3D9;padding:10px;border-radius:12px;font-size:.75rem;margin-bottom:1rem;color:#B45309}
.back{display:block;text-align:center;margin-top:1rem;color:#128A69;text-decoration:none}
</style>
</head>
<body>
<div class="card">
  <h1>🎓 Readiness Enrollment</h1>
  <p style="color:#8A9E96;margin-bottom:1rem">Get certified in seconds</p>
  
  <div class="demo-note">
    📢 Demo: Type "DEMO123" as reference → instant certification
  </div>
  
  <?php if($error): ?>
    <div class="error">❌ <?php echo $error; ?></div>
  <?php endif; ?>
  
  <?php if($success): ?>
    <div class="success">✅ Enrollment successful! Redirecting...</div>
  <?php endif; ?>
  
  <?php if(!$success): ?>
  <form method="POST">
    <div class="option" id="opt1" onclick="selectOption('foundation')">
      <div class="option-header">
        <span class="option-name">Foundation</span>
        <span class="option-price">UGX 30,000</span>
      </div>
      <div class="option-desc">CV template, interview prep, certificate</div>
    </div>
    
    <div class="option" id="opt2" onclick="selectOption('professional')">
      <div class="option-header">
        <span class="option-name">Professional ⭐</span>
        <span class="option-price">UGX 50,000</span>
      </div>
      <div class="option-desc">Priority visibility + 1-on-1 coaching</div>
    </div>
    
    <input type="hidden" name="tier" id="tier" value="foundation">
    
    <div class="field">
      <label>📱 Payment Reference</label>
      <input type="text" name="payment_ref" placeholder="DEMO123 or your reference" required>
    </div>
    
    <button type="submit">✅ Enroll & Get Certified</button>
  </form>
  <?php endif; ?>
  
  <a href="/internhub/readiness.php" class="back">← Back</a>
</div>

<script>
document.getElementById('opt1').classList.add('selected');
function selectOption(tier) {
  document.getElementById('tier').value = tier;
  document.getElementById('opt1').classList.remove('selected');
  document.getElementById('opt2').classList.remove('selected');
  if(tier === 'foundation') {
    document.getElementById('opt1').classList.add('selected');
  } else {
    document.getElementById('opt2').classList.add('selected');
  }
}
</script>
</body>
</html>