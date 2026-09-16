<?php
require 'config.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
 echo json_encode(['success' => false, 'message' => 'Invalid request.']);
 exit;
}
$data = json_decode(file_get_contents('php://input'), true);
$firstName = trim($data['first_name'] ?? '');
$lastName = trim($data['last_name'] ?? '');
$email = trim($data['email'] ?? '');
$password = $data['password'] ?? '';
// Validation — FR-3: Email must be unique
if (!$firstName || !$lastName || !$email || !$password) {
 echo json_encode(['success' => false, 'message' => 'All fields are required.']);
 exit;
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
 echo json_encode(['success' => false, 'message' => 'Invalid email address.']);
 exit;
}
if (strlen($password) < 8) {
 echo json_encode(['success' => false, 'message' => 'Password must be at least 8 characters.']);
 exit;
}
// Check email unique
$stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
$stmt->execute([$email]);
if ($stmt->fetch()) {
 echo json_encode(['success' => false, 'message' => 'This email is already registered.']);
 exit;
}
// Save — password_hash for security
$hashed = password_hash($password, PASSWORD_DEFAULT);
$stmt = $pdo->prepare(
 'INSERT INTO users (first_name, last_name, email, password) VALUES (?, ?, ?, ?)'
);
$stmt->execute([$firstName, $lastName, $email, $hashed]);
echo json_encode(['success' => true, 'message' => 'Account created successfully!']);
?>