<?php
require 'config.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
 echo json_encode(['success' => false, 'message' => 'Invalid request.']);
 exit;
}
$data = json_decode(file_get_contents('php://input'), true);
$email = trim($data['email'] ?? '');
$password = $data['password'] ?? '';
if (!$email || !$password) {
 echo json_encode(['success' => false, 'message' => 'Please fill in all fields.']);
 exit;
}
// UC-5: Verify admin credentials
$stmt = $pdo->prepare('SELECT * FROM users WHERE email = ? AND role = "admin"');
$stmt->execute([$email]);
$admin = $stmt->fetch();
if (!$admin || !password_verify($password, $admin['password'])) {
 echo json_encode(['success' => false, 'message' => 'Invalid admin credentials.']);
 exit;
}
$_SESSION['admin_id'] = $admin['id'];
$_SESSION['admin_name'] = $admin['first_name'];
$_SESSION['role'] = 'admin';
echo json_encode(['success' => true, 'message' => 'Admin login successful!']);
?>