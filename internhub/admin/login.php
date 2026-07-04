<?php
session_start();
if (isset($_SESSION['admin_id']) && $_SESSION['admin_role'] === 'admin') {
    header('Location: /internhub/admin/index.php');
    exit;
}
$error = $_GET['error'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Admin Login — Digital Internship Portal</title>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:wght@400;600;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
<link rel="stylesheet" href="/internhub/css/style.css">
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:'Plus Jakarta Sans',sans-serif;background:#062E22;min-height:100vh;display:flex;align-items:center;justify-content:center;position:relative}
body::before{content:'';position:fixed;inset:0;background:radial-gradient(ellipse 70% 60% at 70% 40%,rgba(18,138,105,.35) 0%,transparent 65%);pointer-events:none}
.login-card{background:#fff;border-radius:28px;width:100%;max-width:420px;margin:2rem;box-shadow:0 24px 80px rgba(6,46,34,.25);overflow:hidden}
.card-header{background:#0B4D3B;padding:2rem;text-align:center}
.card-header-icon{font-size:2.5rem;margin-bottom:.5rem}
.card-header h1{font-family:'Fraunces',serif;font-size:1.5rem;color:#fff;margin-bottom:.25rem}
.card-header p{font-size:.8rem;color:rgba(255,255,255,.55)}
.card-body{padding:2rem}
.alert{background:#FFF1F1;border:1px solid #FECACA;border-radius:12px;padding:.9rem 1.1rem;color:#B91C1C;font-size:.85rem;margin-bottom:1.3rem}
.field{margin-bottom:1.2rem}
label{font-size:.78rem;font-weight:600;color:#0D1F19;display:block;margin-bottom:.4rem}
input{width:100%;padding:12px 14px;font-family:inherit;font-size:.9rem;border:1.5px solid #D8DFDB;border-radius:12px;outline:none;transition:all .2s}
input:focus{border-color:#128A69;box-shadow:0 0 0 3px #EDF9F5}
.btn-submit{width:100%;padding:13px;background:#0B4D3B;color:#fff;font-weight:700;font-size:.95rem;border:none;border-radius:12px;cursor:pointer;transition:all .2s}
.btn-submit:hover{background:#062E22;transform:translateY(-1px)}
.card-footer{padding:1.2rem 2rem;border-top:1px solid #EEF1EF;text-align:center;font-size:.8rem;color:#8A9E96}
.card-footer a{color:#128A69;text-decoration:none;font-weight:600}
</style>
</head>
<body>
<div class="login-card">
  <div class="card-header">
    <div class="card-header-icon">🔐</div>
    <h1>Admin Portal</h1>
    <p>Digital Internship Platform</p>
  </div>
  <div class="card-body">
    <?php if($error): ?>
      <div class="alert">⚠️ <?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <form method="POST" action="/internhub/admin/actions/auth/admin_login.php">
      <div class="field">
        <label>Email Address</label>
        <input type="email" name="email" placeholder="admin@internhub.com" required autofocus/>
      </div>
      <div class="field">
        <label>Password</label>
        <input type="password" name="password" placeholder="••••••••" required/>
      </div>
      <button type="submit" class="btn-submit">Sign In →</button>
    </form>
  </div>
  <div class="card-footer">
    <a href="/internhub/auth/login.php">← Back to Student/Company Login</a>
  </div>
</div>
</body>
</html>