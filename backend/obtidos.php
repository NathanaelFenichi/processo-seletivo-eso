<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['sucesso' => false, 'ids' => []]);
    exit;
}

if (file_exists('../conecta.php')) {
    require '../conecta.php';
} elseif (file_exists('conecta.php')) {
    require 'conecta.php';
} else {
    echo json_encode(['sucesso' => false, 'ids' => []]);
    exit;
}

$userId = $_SESSION['user_id'];

$sql = "SELECT cosmetic_id FROM compras WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

$ids = [];
while ($row = $result->fetch_assoc()) {
    $ids[] = strtolower($row['cosmetic_id']);
}

echo json_encode(['sucesso' => true, 'ids' => $ids]);

$stmt->close();
$conn->close();
?>
