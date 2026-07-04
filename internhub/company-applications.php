<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'company') {
    header('Location: /internhub/auth/login.php');
    exit;
}
require_once 'includes/db.php';
$company_id = $_SESSION['user_id'];

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $app_id = (int)$_POST['app_id'];
    $new_status = $_POST['new_status'];
    $stmt = $pdo->prepare("UPDATE applications SET status = ? WHERE id = ?");
    $stmt->execute([$new_status, $app_id]);
    header('Location: /internhub/company-applications.php?updated=1');
    exit;
}

// Handle placement
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mark_placed'])) {
    $app_id = (int)$_POST['app_id'];
    $internship_id = (int)$_POST['internship_id'];
    $student_id = (int)$_POST['student_id'];
    
    // Update application status to 'placed'
    $pdo->prepare("UPDATE applications SET status = 'placed' WHERE id = ?")->execute([$app_id]);
    
    // Delete any existing placement for this student+internship
    $pdo->prepare("DELETE FROM placements WHERE student_id = ? AND internship_id = ?")->execute([$student_id, $internship_id]);
    
    // Create new placement record
    $pdo->prepare("INSERT INTO placements (student_id, internship_id, company_confirmed, created_at) VALUES (?, ?, 1, NOW())")->execute([$student_id, $internship_id]);
    
    header('Location: /internhub/company-applications.php?placed=1');
    exit;
}

$stmt = $pdo->prepare("
    SELECT a.*, i.title as internship_title, i.company as company_name, u.full_name as student_name, 
           u.email as student_email, u.course, u.student_id,
           u.readiness_certified
    FROM applications a 
    JOIN internships i ON a.listing_id = i.id 
    JOIN users u ON a.student_id = u.id 
    WHERE i.posted_by = ? 
    ORDER BY a.created_at DESC
");
$stmt->execute([$company_id]);
$applications = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Applications Received — DIP</title>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:wght@400;600;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
<link rel="stylesheet" href="/internhub/css/style.css">
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:'Plus Jakarta Sans',sans-serif;background:#F8FAF9;color:#0D1F19;padding:2rem}
.container{max-width:1400px;margin:0 auto}
.header{margin-bottom:2rem}
.header h1{font-family:'Fraunces',serif;font-size:1.8rem;margin-bottom:.25rem}
.header p{color:#8A9E96}
.card{background:#fff;border:1px solid #EEF1EF;border-radius:20px;overflow:hidden;margin-bottom:1.5rem}
table{width:100%;border-collapse:collapse}
th,td{padding:14px 16px;text-align:left;border-bottom:1px solid #EEF1EF;font-size:.85rem}
th{background:#F8FAF9;font-weight:600}
.badge{display:inline-block;padding:4px 10px;border-radius:99px;font-size:.7rem;font-weight:600}
.badge-pending{background:#FFFBEB;color:#B45309}
.badge-reviewed{background:#EFF6FF;color:#1D4ED8}
.badge-shortlisted{background:#F0FDF4;color:#166534}
.badge-rejected{background:#FEF2F2;color:#DC2626}
.badge-placed{background:#DCFCE7;color:#166534;border:1px solid #86EFAC}
.badge-certified{background:#EDF9F5;color:#128A69}
select{padding:6px 10px;border-radius:8px;border:1px solid #D8DFDB}
.btn-submit{padding:6px 12px;background:#0B4D3B;color:#fff;border:none;border-radius:8px;cursor:pointer}
.btn-placed{padding:6px 12px;background:#16A34A;color:#fff;border:none;border-radius:8px;cursor:pointer}
.contact-btn{padding:5px 12px;background:#D4A017;color:#fff;border:none;border-radius:8px;cursor:pointer}
.back-link{display:inline-block;margin-top:1.5rem;color:#128A69;text-decoration:none}
.alert{background:#EDF9F5;border:1px solid #C5F0E3;border-radius:12px;padding:.8rem 1rem;margin-bottom:1.5rem}
.alert-success{background:#F0FDF4;border:1px solid #BBF7D0;color:#16A34A}

/* Modal Styles */
.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.7);
    z-index: 1000;
    overflow: auto;
}
.modal-content {
    background: #fff;
    max-width: 650px;
    margin: 50px auto;
    border-radius: 24px;
    padding: 0;
    position: relative;
    box-shadow: 0 20px 60px rgba(0,0,0,0.3);
    animation: modalFadeIn 0.3s ease;
}
@keyframes modalFadeIn {
    from { opacity: 0; transform: translateY(-30px); }
    to { opacity: 1; transform: translateY(0); }
}
.modal-header {
    padding: 20px 25px;
    background: #0B4D3B;
    color: #fff;
    border-radius: 24px 24px 0 0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.modal-header h2 {
    font-family: 'Fraunces', serif;
    font-size: 1.3rem;
    margin: 0;
}
.modal-close {
    background: none;
    border: none;
    font-size: 28px;
    cursor: pointer;
    color: #fff;
    opacity: 0.7;
    transition: opacity 0.2s;
}
.modal-close:hover {
    opacity: 1;
}
.modal-body {
    padding: 25px;
    max-height: 500px;
    overflow-y: auto;
}
.modal-section {
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 1px solid #EEF1EF;
}
.modal-section:last-child {
    border-bottom: none;
    margin-bottom: 0;
    padding-bottom: 0;
}
.modal-section h3 {
    font-family: 'Fraunces', serif;
    font-size: 1rem;
    color: #0B4D3B;
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.cover-letter-box {
    background: #F8FAF9;
    padding: 15px;
    border-radius: 12px;
    font-size: 0.85rem;
    line-height: 1.65;
    color: #333;
    max-height: 200px;
    overflow-y: auto;
    white-space: pre-wrap;
    word-wrap: break-word;
}
.file-link {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: #EDF9F5;
    padding: 8px 15px;
    border-radius: 8px;
    color: #128A69;
    text-decoration: none;
    font-weight: 600;
    font-size: 0.8rem;
    transition: background 0.2s;
}
.file-link:hover {
    background: #C5F0E3;
}
.portfolio-link {
    color: #128A69;
    text-decoration: none;
    word-break: break-all;
}
.portfolio-link:hover {
    text-decoration: underline;
}
.info-row {
    display: flex;
    margin-bottom: 8px;
}
.info-label {
    font-weight: 600;
    width: 100px;
    flex-shrink: 0;
    color: #4A5E56;
}
.info-value {
    color: #0D1F19;
    flex: 1;
}
.btn-view-app {
    background: #128A69;
    color: #fff;
    padding: 6px 12px;
    border-radius: 8px;
    border: none;
    cursor: pointer;
    font-size: 0.7rem;
    font-weight: 600;
}
.btn-view-app:hover {
    background: #0B5D45;
}
.modal-actions {
    margin-top: 20px;
    padding-top: 15px;
    border-top: 1px solid #EEF1EF;
    display: flex;
    gap: 10px;
    justify-content: flex-end;
}
</style>
</head>
<body>
<div class="container">
  <div class="header">
    <h1>📥 Applications Received</h1>
    <p>Review, shortlist, or confirm placements for student applications.</p>
  </div>

  <?php if(isset($_GET['updated'])): ?>
    <div class="alert">✅ Application status updated.</div>
  <?php endif; ?>

  <?php if(isset($_GET['placed'])): ?>
    <div class="alert alert-success">✅ Student marked as placed! Admin can now confirm.</div>
  <?php endif; ?>

  <div class="card">
    <?php if(empty($applications)): ?>
      <div style="padding:3rem;text-align:center;color:#8A9E96">No applications received yet.</div>
    <?php else: ?>
      <div style="overflow-x:auto;">
        <table>
          <thead>
            <tr><th>Student</th><th>Course</th><th>Internship</th><th>Applied</th><th>Readiness</th><th>Status</th><th>Actions</th></tr>
          </thead>
          <tbody>
            <?php foreach($applications as $app): ?>
            <tr style="<?php echo $app['status'] == 'placed' ? 'background:#F0FDF4;' : ''; ?>">
              <td>
                <strong><?php echo htmlspecialchars($app['student_name']); ?></strong><br>
                <span style="font-size:.7rem;color:#666"><?php echo htmlspecialchars($app['student_email']); ?></span>
              </td>
              <td><?php echo htmlspecialchars($app['course'] ?? '—'); ?></td>
              <td><?php echo htmlspecialchars($app['internship_title']); ?><br><span style="font-size:.7rem;color:#999"><?php echo htmlspecialchars($app['company_name']); ?></span></td>
              <td><?php echo date('d M Y', strtotime($app['created_at'])); ?></td>
              <td><?php echo $app['readiness_certified'] ? '<span class="badge badge-certified">✅ Certified</span>' : '<span style="color:#999">Not certified</span>'; ?></td>
              <td>
                <span class="badge badge-<?php echo $app['status']; ?>">
                  <?php 
                  if($app['status'] == 'shortlisted') echo '⭐ Shortlisted';
                  elseif($app['status'] == 'reviewed') echo '👀 Reviewed';
                  elseif($app['status'] == 'placed') echo '✅ Placed';
                  elseif($app['status'] == 'rejected') echo '❌ Rejected';
                  else echo '⏳ Pending';
                  ?>
                </span>
              </td>
              <td style="white-space: nowrap;">
                <button onclick="viewApplication(<?php echo $app['id']; ?>)" class="btn-view-app">👁 View Application</button>
                
                <?php if($app['status'] != 'placed' && $app['status'] != 'rejected'): ?>
                <form method="POST" style="display:inline-block; margin-top: 5px;">
                  <input type="hidden" name="app_id" value="<?php echo $app['id']; ?>">
                  <select name="new_status">
                    <option value="pending" <?php echo $app['status']==='pending'?'selected':''; ?>>Pending</option>
                    <option value="reviewed" <?php echo $app['status']==='reviewed'?'selected':''; ?>>Reviewed</option>
                    <option value="shortlisted" <?php echo $app['status']==='shortlisted'?'selected':''; ?>>Shortlist</option>
                    <option value="rejected" <?php echo $app['status']==='rejected'?'selected':''; ?>>Reject</option>
                  </select>
                  <button type="submit" name="update_status" class="btn-submit">Update</button>
                </form>
                <?php endif; ?>
                
                <?php if($app['status'] == 'shortlisted'): ?>
                <form method="POST" style="display:inline-block; margin-top: 5px;">
                  <input type="hidden" name="app_id" value="<?php echo $app['id']; ?>">
                  <input type="hidden" name="internship_id" value="<?php echo $app['listing_id']; ?>">
                  <input type="hidden" name="student_id" value="<?php echo $app['student_id']; ?>">
                  <button type="submit" name="mark_placed" class="btn-placed" onclick="return confirm('Confirm: This student has been placed at your organization?')">✅ Confirm Placement</button>
                </form>
                <?php endif; ?>
                
                <button onclick="alert('Email: <?php echo $app['student_email']; ?>')" class="contact-btn" style="margin-top: 5px;">📧 Contact</button>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>
  <a href="/internhub/auth/dashboard.php" class="back-link">← Back to Dashboard</a>
</div>

<!-- Modal for Viewing Application Details - COMPLETELY RESTORED -->
<div id="appModal" class="modal">
  <div class="modal-content">
    <div class="modal-header">
      <h2>📋 Application Details</h2>
      <button class="modal-close" onclick="closeModal()">&times;</button>
    </div>
    <div class="modal-body" id="modalBody">
      <div style="text-align:center; padding:40px;">Loading application details...</div>
    </div>
  </div>
</div>

<script>
function viewApplication(appId) {
    const modal = document.getElementById('appModal');
    const modalBody = document.getElementById('modalBody');
    
    modal.style.display = 'block';
    modalBody.innerHTML = '<div style="text-align:center; padding:40px;">📂 Loading application details...</div>';
    
    fetch('/internhub/company/get_application_details.php?id=' + appId)
        .then(response => response.json())
        .then(data => {
            let cvHtml = '';
            let portfolioHtml = '';
            let coverLetterHtml = '';
            
            // Cover letter
            if (data.cover_letter && data.cover_letter.trim() !== '') {
                coverLetterHtml = `
                    <div class="modal-section">
                        <h3>📄 Cover Letter</h3>
                        <div class="cover-letter-box">${escapeHtml(data.cover_letter)}</div>
                    </div>
                `;
            } else {
                coverLetterHtml = `
                    <div class="modal-section">
                        <h3>📄 Cover Letter</h3>
                        <div class="cover-letter-box" style="color:#999;">No cover letter provided.</div>
                    </div>
                `;
            }
            
            // CV download
            if (data.cv_path && data.cv_path.trim() !== '') {
                cvHtml = `
                    <div class="modal-section">
                        <h3>📎 CV / Resume</h3>
                        <a href="/internhub/${data.cv_path}" target="_blank" class="file-link">
                            📄 Download CV (PDF/DOC)
                        </a>
                    </div>
                `;
            } else {
                cvHtml = `
                    <div class="modal-section">
                        <h3>📎 CV / Resume</h3>
                        <p style="color:#999;">No CV uploaded.</p>
                    </div>
                `;
            }
            
            // Portfolio link
            if (data.portfolio_link && data.portfolio_link.trim() !== '') {
                portfolioHtml = `
                    <div class="modal-section">
                        <h3>🔗 Portfolio / GitHub</h3>
                        <a href="${data.portfolio_link}" target="_blank" class="portfolio-link">${data.portfolio_link}</a>
                    </div>
                `;
            }
            
            // Build the complete modal HTML
            const html = `
                <div class="modal-section">
                    <div class="info-row">
                        <div class="info-label">Student Name:</div>
                        <div class="info-value"><strong>${escapeHtml(data.student_name)}</strong></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Email:</div>
                        <div class="info-value">${escapeHtml(data.student_email)}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Course:</div>
                        <div class="info-value">${escapeHtml(data.course || 'Not specified')}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Readiness:</div>
                        <div class="info-value">${data.readiness_certified ? '✅ Certified' : '❌ Not certified'}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Applied on:</div>
                        <div class="info-value">${new Date(data.created_at).toLocaleDateString()}</div>
                    </div>
                </div>
                ${coverLetterHtml}
                ${cvHtml}
                ${portfolioHtml}
                <div class="modal-actions">
                    <button onclick="closeModal()" class="btn-submit" style="background:#666;">Close</button>
                </div>
            `;
            
            modalBody.innerHTML = html;
        })
        .catch(error => {
            console.error('Error:', error);
            modalBody.innerHTML = `
                <div style="color:red; padding:40px; text-align:center;">
                    ❌ Error loading application details.<br>
                    Please refresh the page and try again.
                </div>
                <div style="text-align:center; padding-bottom:20px;">
                    <button onclick="closeModal()" class="btn-submit" style="background:#666;">Close</button>
                </div>
            `;
        });
}

function closeModal() { 
    document.getElementById('appModal').style.display = 'none'; 
}

function escapeHtml(str) { 
    if(!str) return ''; 
    return str.replace(/[&<>]/g, function(m){
        if(m==='&') return '&amp;'; 
        if(m==='<') return '&lt;'; 
        if(m==='>') return '&gt;'; 
        return m;
    }).replace(/\n/g, '<br>');
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('appModal');
    if (event.target === modal) {
        closeModal();
    }
}
</script>
</body>
</html>