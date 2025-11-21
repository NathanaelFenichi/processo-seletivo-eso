<?php
session_start();
header('Content-Type: application/json');

//  Verifica Login
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['tem_item' => false, 'msg' => 'Não logado']);
    exit;
}

// Conecta ao Banco
if (file_exists('../conecta.php')) {
    require '../conecta.php';
} elseif (file_exists('conecta.php')) {
    require 'conecta.php';
} else {
    echo json_encode(['tem_item' => false, 'error' => 'Erro conexao']);
    exit;
}

// 3. Pega dados
$userId = $_SESSION['user_id'];
$input = json_decode(file_get_contents('php://input'), true);
$itemId = $input['id'] ?? null;

if (!$itemId) {
    echo json_encode(['tem_item' => false]);
    exit;
}

// consulta SQL
// Verifica se existe registro na tabela 'compras'
$sql = "SELECT id FROM compras WHERE user_id = ? AND cosmetic_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $userId, $itemId);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo json_encode(['tem_item' => true]);
} else {
    echo json_encode(['tem_item' => false]);
}

$stmt->close();
$conn->close();
?>