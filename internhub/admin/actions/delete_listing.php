<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: /internhub/admin/login.php');
    exit;
}
require_once '../../includes/db.php';

$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("DELETE FROM internships WHERE id = :id");
$stmt->execute([':id' => $id]);

header('Location: /internhub/admin/manage-listings.php?success=deleted');
exit;
?>