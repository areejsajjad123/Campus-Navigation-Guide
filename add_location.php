<?php
require 'config.php';
if (($_SESSION['role'] ?? '') !== 'admin') {
 echo json_encode(['success' => false, 'message' => 'Unauthorized.']);
 exit;
}
$data = json_decode(file_get_contents('php://input'), true);
$name = trim($data['name'] ?? '');
$type = trim($data['type'] ?? '');
$latitude = floatval($data['latitude'] ?? 0);
$longitude = floatval($data['longitude'] ?? 0);
if (!$name || !$type || !$latitude || !$longitude) {
 echo json_encode(['success' => false, 'message' => 'All fields are required.']);
 exit;
}
$stmt = $pdo->prepare(
 'INSERT INTO locations (name, type, latitude, longitude) VALUES (?, ?, ?, ?)'
);
$stmt->execute([$name, $type, $latitude, $longitude]);
echo json_encode([
 'success' => true,
 'message' => 'Location added successfully!',
 'id' => $pdo->lastInsertId()
]);
?>
