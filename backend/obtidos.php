<?php
session_start();
header('Content-Type: application/json');

// Verifica se está logado
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['sucesso' => false, 'ids' => []]);
    exit;
}

// Conexão com Banco
if (file_exists('../conecta.php')) {
    require '../conecta.php';
} elseif (file_exists('conecta.php')) {
    require 'conecta.php';
} else {
    echo json_encode(['sucesso' => false, 'ids' => []]);
    exit;
}

$userId = $_SESSION['user_id'];

// --- IMPORTANTE: Buscamos TODOS os itens, sem filtro de ID específico ---
$sql = "SELECT cosmetic_id FROM compras WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

$ids = [];
while ($row = $result->fetch_assoc()) {
    $ids[] = strtolower($row['cosmetic_id']); // Salva tudo num array
}

// Retorna a lista completa (ex: ids: ['cid_123', 'cid_456'])
echo json_encode(['sucesso' => true, 'ids' => $ids]);

$stmt->close();
$conn->close();
?>