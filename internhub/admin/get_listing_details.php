<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    http_response_code(401);
    exit;
}
require_once '../includes/db.php';

$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT * FROM internships WHERE id = ?");
$stmt->execute([$id]);
$listing = $stmt->fetch();

if (!$listing) {
    http_response_code(404);
    exit;
}

header('Content-Type: application/json');
echo json_encode($listing);
?>