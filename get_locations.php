<?php
require 'config.php';
// All 17 locations fetch karo
$stmt = $pdo->query('SELECT * FROM locations ORDER BY type, name');
$locations = $stmt->fetchAll();
echo json_encode(['success' => true, 'locations' => $locations]);
?>
