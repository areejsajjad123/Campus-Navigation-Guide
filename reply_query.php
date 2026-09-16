<?php
require 'config.php';
// Only admin can reply
if (($_SESSION['role'] ?? '') !== 'admin') {
 echo json_encode(['success' => false, 'message' => 'Unauthorized access.']);
 exit;
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
 echo json_encode(['success' => false, 'message' => 'Invalid request.']);
 exit;
}
$data = json_decode(file_get_contents('php://input'), true);
$queryId = intval($data['query_id'] ?? 0);
$reply = trim($data['reply'] ?? '');
$status = $data['status'] ?? 'In Progress';
$adminId = $_SESSION['admin_id'] ?? null;
// FR-6: Reply must not be empty
if (!$queryId || !$reply) {
 echo json_encode(['success' => false, 'message' => 'Query ID and reply are required.']);
 exit;
}
// FR-6: Status must be valid
$validStatuses = ['Pending', 'In Progress', 'Resolved'];
if (!in_array($status, $validStatuses)) {
 $status = 'In Progress';
}
// Save reply — R6: Store in database
$stmt = $pdo->prepare(
 'INSERT INTO helpdesk_replies (query_id, admin_id, reply) VALUES (?, ?, ?)'
);
$stmt->execute([$queryId, $adminId, $reply]);
// FR-6: Update query status
$stmt = $pdo->prepare('UPDATE helpdesk_queries SET status = ? WHERE id = ?');
$stmt->execute([$status, $queryId]);
echo json_encode(['success' => true, 'message' => 'Reply sent successfully!']);
?>