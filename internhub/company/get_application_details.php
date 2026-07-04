<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'company') {
    http_response_code(401);
    exit;
}
require_once '../includes/db.php';

$id = (int)$_GET['id'];
$stmt = $pdo->prepare("
    SELECT a.*, u.full_name as student_name, u.email as student_email, u.course, u.readiness_certified 
    FROM applications a 
    JOIN users u ON a.student_id = u.id 
    WHERE a.id = ?
");
$stmt->execute([$id]);
$app = $stmt->fetch();

header('Content-Type: application/json');
echo json_encode($app);
?>