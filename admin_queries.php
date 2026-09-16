<?php
require 'config.php';
// R7: Only admin can access
if (($_SESSION['role'] ?? '') !== 'admin') {
 echo json_encode(['success' => false, 'message' => 'Unauthorized access.']);
 exit;
}
$status = $_GET['status'] ?? 'all';
if ($status === 'all') {
 $stmt = $pdo->query('
 SELECT
 q.id, q.name, q.email, q.message,
 q.status, q.created_at,
 r.reply, r.created_at AS reply_date
 FROM helpdesk_queries q
 LEFT JOIN helpdesk_replies r ON r.query_id = q.id
 ORDER BY q.created_at DESC
 ');
} else {
 $stmt = $pdo->prepare('
 SELECT
 q.id, q.name, q.email, q.message,
 q.status, q.created_at,
 r.reply, r.created_at AS reply_date
 FROM helpdesk_queries q
 LEFT JOIN helpdesk_replies r ON r.query_id = q.id
 WHERE q.status = ?
 ORDER BY q.created_at DESC
 ');
 $stmt->execute([$status]);
}
$queries = $stmt->fetchAll();
echo json_encode(['success' => true, 'queries' => $queries]);
?>
