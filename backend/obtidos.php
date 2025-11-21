<?php
session_start();
// Conecta ao banco
if (file_exists('../conecta.php')) require '../conecta.php';
else require 'conecta.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['tem_item' => false]);
    exit;
}

$userId = $_SESSION['user_id'];
$input = json_decode(file_get_contents('php://input'), true);
$itemId = $input['id'] ?? '';

// Verifica na tabela 'compras' se existe registro desse usuário com esse item
$stmt = $conn->prepare("SELECT id FROM compras WHERE user_id = ? AND cosmetic_id = ?");
$stmt->bind_param("is", $userId, $itemId);
$stmt->execute();
$result = $stmt->get_result();

echo json_encode(['tem_item' => $result->num_rows > 0]);
?>