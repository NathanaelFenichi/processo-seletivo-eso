<?php
session_start();
require '../conecta.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['sucesso' => false, 'msg' => 'Login necessário.']);
    exit;
}

$userId = $_SESSION['user_id'];
$input = json_decode(file_get_contents('php://input'), true);
$idCompra = $input['id_compra'] ?? null; // ID da linha na tabela compras, não do item API

if (!$idCompra) {
    echo json_encode(['sucesso' => false, 'msg' => 'Compra não especificada.']);
    exit;
}

try {
    $conn->begin_transaction();

    // 1. Pega o valor pago para devolver
    $stmt = $conn->prepare("SELECT preco FROM compras WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $idCompra, $userId);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 0) {
        throw new Exception("Compra não encontrada ou não pertence a você.");
    }

    $compra = $res->fetch_assoc();
    $valorReembolso = $compra['preco'];

    // 2. Deleta a compra
    $stmtDel = $conn->prepare("DELETE FROM compras WHERE id = ?");
    $stmtDel->bind_param("i", $idCompra);
    $stmtDel->execute();

    // 3. Devolve o dinheiro (UPDATE com SOMA)
    $stmtUp = $conn->prepare("UPDATE usuarios SET vbucks = vbucks + ? WHERE ID = ?");
    $stmtUp->bind_param("ii", $valorReembolso, $userId);
    $stmtUp->execute();

    $conn->commit();
    
    // Atualiza sessão (opcional, pra visualização rápida)
    $_SESSION['vbucks'] += $valorReembolso;

    echo json_encode(['sucesso' => true, 'msg' => 'Item devolvido e V-Bucks reembolsados!']);

} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['sucesso' => false, 'msg' => 'Erro: ' . $e->getMessage()]);
}
?>