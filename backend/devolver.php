<?php
// Limpa qualquer saída de texto anterior para não quebrar o JSON
ob_clean();
error_reporting(E_ALL);
ini_set('display_errors', 0); // Não mostra erros na tela (para não quebrar o JSON)

session_start();
header('Content-Type: application/json');

// Função auxiliar para responder e morrer
function responder($sucesso, $msg) {
    echo json_encode(['sucesso' => $sucesso, 'msg' => $msg]);
    exit;
}

// 1. Verifica Login
if (!isset($_SESSION['user_id'])) {
    responder(false, 'Você precisa estar logado.');
}

// 2. Conexão (Tenta achar o arquivo)
if (file_exists('../conecta.php')) {
    require '../conecta.php';
} elseif (file_exists('conecta.php')) {
    require 'conecta.php';
} else {
    responder(false, 'Erro interno: Arquivo de conexão não encontrado.');
}

$userId = $_SESSION['user_id'];
$input = json_decode(file_get_contents('php://input'), true);
$idCompra = $input['id_compra'] ?? null;

if (!$idCompra) {
    responder(false, 'ID da compra não identificado.');
}

try {
    // Inicia Transação
    $conn->begin_transaction();

    // A. Verifica se a compra existe e pertence ao usuário
    // NOTA: Verifique se sua tabela se chama 'compras' e a coluna do usuário é 'user_id'
    $stmt = $conn->prepare("SELECT id, preco FROM compras WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $idCompra, $userId);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 0) {
        throw new Exception("Compra não encontrada ou não pertence a você.");
    }

    $compra = $res->fetch_assoc();
    $valorReembolso = $compra['preco'];

    // B. Deleta o registro da compra
    $stmtDel = $conn->prepare("DELETE FROM compras WHERE id = ?");
    $stmtDel->bind_param("i", $idCompra);
    $stmtDel->execute();

    // C. Devolve o dinheiro (UPDATE)
    // NOTA: Verifique se na tabela 'usuarios' a coluna é 'vbucks' e o ID é 'ID'
    $stmtUp = $conn->prepare("UPDATE usuarios SET vbucks = vbucks + ? WHERE ID = ?");
    $stmtUp->bind_param("ii", $valorReembolso, $userId);
    $stmtUp->execute();

    $conn->commit();
    
    // Atualiza a sessão para refletir na hora
    $_SESSION['vbucks'] += $valorReembolso;

    responder(true, 'Item devolvido com sucesso!');

} catch (Exception $e) {
    $conn->rollback();
    responder(false, 'Erro no banco: ' . $e->getMessage());
}
?>