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
// FR-4: Valid credentials check
$stmt = $pdo->prepare('SELECT * FROM users WHERE email = ? AND role = "user"');
$stmt->execute([$email]);
$user = $stmt->fetch();
if (!$user || !password_verify($password, $user['password'])) {
 echo json_encode(['success' => false, 'message' => 'Invalid email or password.']);
 exit;
}
// Session start — R9: Authentication
$_SESSION['user_id'] = $user['id'];
$_SESSION['user_name'] = $user['first_name'];
$_SESSION['user_email'] = $user['email'];
$_SESSION['role'] = 'user';
echo json_encode([
 'success' => true,
 'message' => 'Login successful!',
 'name' => $user['first_name']
]);
?>
