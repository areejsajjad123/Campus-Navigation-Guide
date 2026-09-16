<?php
require 'config.php';
if (($_SESSION['role'] ?? '') !== 'admin') {
 echo json_encode(['success' => false, 'message' => 'Unauthorized.']);
 exit;
}
$data = json_decode(file_get_contents('php://input'), true);
$id = intval($data['id'] ?? 0);
$name = trim($data['name'] ?? '');
$type = trim($data['type'] ?? '');
$latitude = floatval($data['latitude'] ?? 0);
$longitude = floatval($data['longitude'] ?? 0);
if (!$id || !$name || !$type) {
 echo json_encode(['success' => false, 'message' => 'All fields are required.']);
 exit;
}
$stmt = $pdo->prepare(
 'UPDATE locations SET name=?, type=?, latitude=?, longitude=? WHERE id=?'
);
$stmt->execute([$name, $type, $latitude, $longitude, $id]);
echo json_encode(['success' => true, 'message' => 'Location updated successfully!']);
?>