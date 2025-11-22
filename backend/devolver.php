<?php
// 1. Inicia sessão ANTES de qualquer coisa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. Define que é JSON (Obrigatório)
header('Content-Type: application/json; charset=utf-8');

// Configura para não mostrar erros na tela (evita quebrar o JSON com Warnings)
error_reporting(E_ALL);
ini_set('display_errors', 0);

// Função auxiliar para responder
function responder($sucesso, $msg) {
    echo json_encode(['sucesso' => $sucesso, 'msg' => $msg]);
    exit;
}

// 3. Conexão
// Ajustei para procurar no diretório atual primeiro, que é o padrão do Render
if (file_exists('conecta.php')) {
    require 'conecta.php';
} elseif (file_exists('../conecta.php')) {
    require '../conecta.php';
} else {
    responder(false, 'Erro interno: Arquivo de conexão não encontrado.');
}

// 4. Verifica Login (BLINDAGEM)
// Tenta pegar o ID, seja ele salvo como 'user_id' ou 'ID' ou 'id'
$idUsuario = $_SESSION['user_id'] ?? $_SESSION['ID'] ?? $_SESSION['id'] ?? null;

if (!$idUsuario) {
    responder(false, 'Você precisa estar logado para devolver um item.');
}

// Recebe o JSON do Frontend
$input = json_decode(file_get_contents('php://input'), true);
$idCompra = $input['id_compra'] ?? null;

if (!$idCompra) {
    responder(false, 'ID da compra não identificado.');
}

try {
    // Inicia Transação (Tudo ou Nada)
    $conn->begin_transaction();

    // A. Verifica se a compra existe e pertence ao usuário
    $stmt = $conn->prepare("SELECT id, preco FROM compras WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $idCompra, $idUsuario);
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

    // C. Devolve o dinheiro (UPDATE na tabela usuarios)
    $stmtUp = $conn->prepare("UPDATE usuarios SET vbucks = vbucks + ? WHERE ID = ?");
    $stmtUp->bind_param("ii", $valorReembolso, $idUsuario);
    $stmtUp->execute();

    // Confirma as alterações no banco
    $conn->commit();
    
    // Atualiza a sessão para o usuário ver o saldo novo na hora (se a sessão tiver o campo vbucks)
    if (isset($_SESSION['vbucks'])) {
        $_SESSION['vbucks'] += $valorReembolso;
    }

    responder(true, 'Item devolvido e V-Bucks reembolsados!');

} catch (Exception $e) {
    // Se der erro, desfaz tudo
    $conn->rollback();
    responder(false, 'Erro ao processar devolução: ' . $e->getMessage());
}
?>