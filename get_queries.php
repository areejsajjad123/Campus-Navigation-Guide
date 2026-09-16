<?php
require 'config.php';
// Check login
if (empty($_SESSION['user_id'])) {
 echo json_encode(['success' => false, 'message' => 'Not logged in.', 'redirect' => 'login.html']);
 exit;
}
$userId = $_SESSION['user_id'];
// Fetch queries with admin replies
$stmt = $pdo->prepare('
 SELECT
 q.id,
 q.name,
 q.email,
 q.message,
 q.status,
 q.created_at,
 r.reply,
 r.created_at AS reply_date
 FROM helpdesk_queries q
 LEFT JOIN helpdesk_replies r ON r.query_id = q.id
 WHERE q.user_id = ?
 ORDER BY q.created_at DESC
');
$stmt->execute([$userId]);
$queries = $stmt->fetchAll();
echo json_encode(['success' => true, 'queries' => $queries]);
?>
