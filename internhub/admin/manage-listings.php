<?php
session_start();
if (!isset($_SESSION['admin_id']) || $_SESSION['admin_role'] !== 'admin') {
    header('Location: /internhub/admin/login.php');
    exit;
}
require_once '../includes/db.php';

$statusFilter = $_GET['status'] ?? 'all';
$sql = "SELECT i.*, u.full_name as company_name FROM internships i LEFT JOIN users u ON i.posted_by = u.id";
if ($statusFilter !== 'all') {
    $sql .= " WHERE i.status = :status";
}
$sql .= " ORDER BY i.created_at DESC";
$stmt = $pdo->prepare($sql);
if ($statusFilter !== 'all') {
    $stmt->execute([':status' => $statusFilter]);
} else {
    $stmt->execute();
}
$listings = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<title>Manage Listings — Admin</title>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:wght@400;600;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
<link rel="stylesheet" href="/internhub/css/style.css">
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:'Plus Jakarta Sans',sans-serif;background:#F8FAF9;color:#0D1F19}
.sidebar{position:fixed;top:0;left:0;width:260px;height:100vh;background:#062E22;display:flex;flex-direction:column}
.sb-header{padding:1.5rem;border-bottom:1px solid rgba(255,255,255,.1)}
.sb-header h2{font-family:'Fraunces',serif;font-size:1rem;color:#fff}
.sb-nav{padding:1rem .75rem;flex:1}
.sb-item{display:flex;align-items:center;gap:.8rem;padding:.7rem .9rem;border-radius:10px;font-size:.85rem;font-weight:500;color:rgba(255,255,255,.5);transition:all .2s;margin-bottom:.2rem}
.sb-item:hover{background:rgba(255,255,255,.08);color:#fff}
.sb-item.active{background:rgba(29,184,133,.15);color:#1DB885}
.sb-logout{margin-top:auto;padding:1rem .75rem;border-top:1px solid rgba(255,255,255,.1)}
.sb-logout a{display:flex;align-items:center;gap:.8rem;padding:.7rem .9rem;border-radius:10px;font-size:.85rem;color:rgba(255,100,100,.6)}
.sb-logout a:hover{background:rgba(248,113,113,.1);color:#f87171}
.main{margin-left:260px;padding:1.5rem}
.topbar{background:#fff;border-radius:16px;padding:1rem 1.5rem;margin-bottom:1.5rem;display:flex;justify-content:space-between;align-items:center}
.topbar h1{font-family:'Fraunces',serif;font-size:1.3rem}
.filters{display:flex;gap:.5rem;margin-bottom:1rem}
.filter-btn{padding:8px 16px;border-radius:99px;font-size:.8rem;font-weight:600;background:#fff;border:1px solid #D8DFDB;text-decoration:none;color:#4A5E56}
.filter-btn.active{background:#0B4D3B;border-color:#0B4D3B;color:#fff}
.card{background:#fff;border:1px solid #EEF1EF;border-radius:20px;overflow:hidden}
table{width:100%;border-collapse:collapse}
th,td{padding:14px 16px;text-align:left;border-bottom:1px solid #EEF1EF;font-size:.85rem}
th{background:#F8FAF9;font-weight:600;color:#4A5E56}
.status-pending{background:#FFFBEB;color:#B45309;padding:4px 10px;border-radius:99px;font-size:.7rem;font-weight:600;display:inline-block}
.status-approved{background:#F0FDF4;color:#166534;padding:4px 10px;border-radius:99px;font-size:.7rem;font-weight:600;display:inline-block}
.status-rejected{background:#FEF2F2;color:#DC2626;padding:4px 10px;border-radius:99px;font-size:.7rem;font-weight:600;display:inline-block}
.btn-group{display:flex;gap:.4rem;flex-wrap:wrap}
.btn-sm{padding:5px 12px;border-radius:8px;font-size:.7rem;font-weight:600;border:none;cursor:pointer}
.btn-approve{background:#16A34A;color:#fff}
.btn-reject{background:#DC2626;color:#fff}
.btn-delete{background:#9CA3AF;color:#fff}
.btn-view{background:#128A69;color:#fff}
.modal{display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.7);z-index:1000;overflow:auto}
.modal-content{background:#fff;max-width:700px;margin:50px auto;border-radius:20px;padding:25px;position:relative}
.modal-close{position:absolute;top:15px;right:20px;background:none;border:none;font-size:24px;cursor:pointer}
.modal-close:hover{color:#DC2626}
.modal h2{font-family:'Fraunces',serif;margin-bottom:15px}
.modal hr{margin:15px 0}
.modal ul{padding-left:20px;margin:10px 0}
.modal li{margin:5px 0}
</style>
</head>
<body>
<div class="sidebar">
  <div class="sb-header"><h2>⚙️ Admin Portal</h2></div>
  <div class="sb-nav">
    <a href="/internhub/admin/index.php" class="sb-item">🏠 Dashboard</a>
    <a href="/internhub/admin/manage-listings.php" class="sb-item active">📋 Manage Listings</a>
    <a href="/internhub/admin/manage-payments.php" class="sb-item">💰 Payments</a>
    <a href="/internhub/admin/manage-users.php" class="sb-item">👥 Users</a>
    <a href="/internhub/admin/verify-placement.php" class="sb-item">✅ Placements</a>
  </div>
  <div class="sb-logout"><a href="/internhub/admin/actions/admin_logout.php">🚪 Logout</a></div>
</div>

<div class="main">
  <div class="topbar"><h1>Manage Internship Listings</h1></div>
  
  <div class="filters">
    <a href="?status=all" class="filter-btn <?php echo $statusFilter==='all'?'active':''; ?>">All</a>
    <a href="?status=pending" class="filter-btn <?php echo $statusFilter==='pending'?'active':''; ?>">Pending</a>
    <a href="?status=approved" class="filter-btn <?php echo $statusFilter==='approved'?'active':''; ?>">Approved</a>
    <a href="?status=rejected" class="filter-btn <?php echo $statusFilter==='rejected'?'active':''; ?>">Rejected</a>
  </div>

  <div class="card">
    <div style="overflow-x:auto;">
      <table>
        <thead>
          <tr><th>ID</th><th>Title</th><th>Company</th><th>Posted By</th><th>Deadline</th><th>Status</th><th>Actions</th></tr>
        </thead>
        <tbody>
          <?php foreach($listings as $l): ?>
          <tr>
            <td><?php echo $l['id']; ?></td>
            <td><strong><?php echo htmlspecialchars($l['title']); ?></strong><br><span style="font-size:.7rem;color:#8A9E96"><?php echo htmlspecialchars($l['field']); ?></span></td>
            <td><?php echo htmlspecialchars($l['company']); ?></td>
            <td><?php echo htmlspecialchars($l['company_name'] ?? '—'); ?></td>
            <td><?php echo date('d M Y', strtotime($l['deadline'])); ?></td>
            <td><span class="status-<?php echo $l['status']; ?>"><?php echo ucfirst($l['status']); ?></span></td>
            <td class="btn-group">
              <button onclick="viewDetails(<?php echo $l['id']; ?>)" class="btn-sm btn-view">👁 View Details</button>
              <?php if($l['status'] === 'pending'): ?>
                <a href="/internhub/admin/actions/approve_listing.php?id=<?php echo $l['id']; ?>" class="btn-sm btn-approve" onclick="return confirm('Approve this listing?')">Approve</a>
                <a href="/internhub/admin/actions/reject_listing.php?id=<?php echo $l['id']; ?>" class="btn-sm btn-reject" onclick="return confirm('Reject this listing?')">Reject</a>
              <?php endif; ?>
              <a href="/internhub/admin/actions/delete_listing.php?id=<?php echo $l['id']; ?>" class="btn-sm btn-delete" onclick="return confirm('Permanently delete this listing?')">Delete</a>
            </td>
          </tr>
          <?php endforeach; ?>
          <?php if(empty($listings)): ?>
          <tr><td colspan="7" style="text-align:center;padding:2rem">No listings found</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Modal for Viewing Details -->
<div id="detailModal" class="modal">
  <div class="modal-content">
    <button class="modal-close" onclick="closeModal()">&times;</button>
    <div id="modalContent">Loading...</div>
  </div>
</div>

<script>
function viewDetails(id) {
    document.getElementById('detailModal').style.display = 'block';
    document.getElementById('modalContent').innerHTML = '<div style="text-align:center; padding:40px;">Loading internship details...</div>';
    
    fetch('/internhub/admin/get_listing_details.php?id=' + id)
        .then(response => response.json())
        .then(data => {
            let respItems = '';
            if (data.responsibilities) {
                respItems = data.responsibilities.split('|').map(r => `<li>${escapeHtml(r)}</li>`).join('');
            }
            let reqItems = '';
            if (data.requirements) {
                reqItems = data.requirements.split('|').map(r => `<li>${escapeHtml(r)}</li>`).join('');
            }
            
            let html = `
                <h2>${escapeHtml(data.title)}</h2>
                <p><strong>Company:</strong> ${escapeHtml(data.company)}</p>
                <p><strong>Location:</strong> ${escapeHtml(data.location)}</p>
                <p><strong>Field:</strong> ${escapeHtml(data.field)}</p>
                <p><strong>Duration:</strong> ${data.duration} months</p>
                <p><strong>Stipend:</strong> ${escapeHtml(data.stipend)}</p>
                <p><strong>Deadline:</strong> ${data.deadline}</p>
                <p><strong>Slots:</strong> ${data.slots}</p>
                <p><strong>Contact:</strong> ${escapeHtml(data.contact)}</p>
                <hr>
                <h3>📋 Description</h3>
                <p>${escapeHtml(data.description)}</p>
                <h3>⚡ Responsibilities</h3>
                <ul>${respItems || '<li>Not specified</li>'}</ul>
                <h3>📜 Requirements</h3>
                <ul>${reqItems || '<li>Not specified</li>'}</ul>
                <hr>
                <div style="display:flex; gap:10px; margin-top:20px; flex-wrap:wrap;">
                    ${data.status === 'pending' ? `
                        <a href="/internhub/admin/actions/approve_listing.php?id=${data.id}" class="btn-sm btn-approve" style="padding:10px 20px;" onclick="return confirm('Approve this listing?')">✅ Approve</a>
                        <a href="/internhub/admin/actions/reject_listing.php?id=${data.id}" class="btn-sm btn-reject" style="padding:10px 20px;" onclick="return confirm('Reject this listing?')">❌ Reject</a>
                    ` : ''}
                    <button onclick="closeModal()" class="btn-sm" style="padding:10px 20px; background:#666; color:#fff;">Close</button>
                </div>
            `;
            document.getElementById('modalContent').innerHTML = html;
        })
        .catch(error => {
            document.getElementById('modalContent').innerHTML = '<div style="color:red; padding:40px; text-align:center;">Error loading details. Please try again.</div>';
        });
}

function closeModal() {
    document.getElementById('detailModal').style.display = 'none';
}

function escapeHtml(text) {
    if (!text) return '';
    return text.replace(/[&<>]/g, function(m) {
        if (m === '&') return '&amp;';
        if (m === '<') return '&lt;';
        if (m === '>') return '&gt;';
        return m;
    }).replace(/\n/g, '<br>');
}
</script>
</body>
</html>