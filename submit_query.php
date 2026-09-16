<?php
require 'config.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
 echo json_encode(['success' => false, 'message' => 'Invalid request.']);
 exit;
}
$data = json_decode(file_get_contents('php://input'), true);
$name = trim($data['name'] ?? '');
$email = trim($data['email'] ?? '');
$message = trim($data['message'] ?? '');
$userId = $_SESSION['user_id'] ?? null;
// FR-5: Complete and valid query details required
if (!$name || !$email || !$message) {
 echo json_encode(['success' => false, 'message' => 'All fields are required.']);
 exit;
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
 echo json_encode(['success' => false, 'message' => 'Invalid email address.']);
 exit;
}
// Save query — R6: Store in database
$stmt = $pdo->prepare(
 'INSERT INTO helpdesk_queries (user_id, name, email, message) VALUES (?, ?, ?, ?)'
);
$stmt->execute([$userId, $name, $email, $message]);
$queryId = $pdo->lastInsertId();
$trackingId = '#HD-' . str_pad($queryId, 4, '0', STR_PAD_LEFT);
echo json_encode([
 'success' => true,
 'message' => 'Query submitted successfully!',
 'tracking_id' => $trackingId
]);
?>