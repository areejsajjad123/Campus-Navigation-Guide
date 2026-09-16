<?php
require 'config.php';
if (($_SESSION['role'] ?? '') !== 'admin') {
 echo json_encode(['success' => false, 'message' => 'Unauthorized.']);
 exit;
}
$data = json_decode(file_get_contents('php://input'), true);
$id = intval($data['id'] ?? 0);
if (!$id) {
 echo json_encode(['success' => false, 'message' => 'Location ID is required.']);
 exit;
}
$stmt = $pdo->prepare('DELETE FROM locations WHERE id = ?');
$stmt->execute([$id]);
echo json_encode(['success' => true, 'message' => 'Location deleted successfully!']);
?>
