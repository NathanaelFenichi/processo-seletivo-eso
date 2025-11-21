<?php
session_start();

// Define que a resposta será sempre JSON
header('Content-Type: application/json');

// 1. Verifica se usuário está logado
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['sucesso' => false, 'msg' => 'Você precisa estar logado para comprar!']);
    exit;
}

// 2. Conexão com Banco de Dados
// Tenta encontrar o arquivo conecta.php voltando uma pasta ou na mesma pasta
if (file_exists('../conecta.php')) {
    require '../conecta.php';
} elseif (file_exists('conecta.php')) {
    require 'conecta.php';
} else {
    echo json_encode(['sucesso' => false, 'msg' => 'Erro interno: Arquivo de conexão não encontrado.']);
    exit;
}

$userId = $_SESSION['user_id'];

// 3. Recebe os dados enviados pelo JavaScript (JSON)
$input = json_decode(file_get_contents('php://input'), true);
$itemId = $input['id'] ?? null;
$preco = $input['preco'] ?? 0;

// Validação básica
if (!$itemId) {
    echo json_encode(['sucesso' => false, 'msg' => 'ID do item inválido.']);
    exit;
}

try {
    // A. Verifica Saldo Atual do Usuário
    $stmt = $conn->prepare("SELECT vbucks FROM usuarios WHERE ID = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $res = $stmt->get_result();
    
    if ($res->num_rows === 0) {
        echo json_encode(['sucesso' => false, 'msg' => 'Usuário não encontrado no banco de dados.']);
        exit;
    }

    $user = $res->fetch_assoc();
    $saldoAtual = $user['vbucks'];

    // Verifica se tem saldo suficiente
    if ($saldoAtual < $preco) {
        echo json_encode(['sucesso' => false, 'msg' => 'V-Bucks insuficientes!']);
        exit;
    }

    // B. Verifica se já comprou este item antes (evita duplicidade)
    $check = $conn->prepare("SELECT id FROM compras WHERE user_id = ? AND cosmetic_id = ?");
    $check->bind_param("is", $userId, $itemId);
    $check->execute();
    if ($check->get_result()->num_rows > 0) {
        echo json_encode(['sucesso' => false, 'msg' => 'Você já possui este item!']);
        exit;
    }

    // C. Realiza a Transação (Desconta Saldo + Registra Compra)
    $conn->begin_transaction();

    // 1. Atualiza o saldo do usuário (desconta o preço)
    $novoSaldo = $saldoAtual - $preco;
    $update = $conn->prepare("UPDATE usuarios SET vbucks = ? WHERE ID = ?");
    $update->bind_param("ii", $novoSaldo, $userId);
    $update->execute();

    // 2. Registra a compra na tabela histórico
    $insert = $conn->prepare("INSERT INTO compras (user_id, cosmetic_id, preco) VALUES (?, ?, ?)");
    $insert->bind_param("isi", $userId, $itemId, $preco);
    $insert->execute();

    // Confirma as alterações no banco
    $conn->commit();

    // Atualiza a sessão PHP para o novo saldo (para a navbar atualizar ao recarregar a página)
    $_SESSION['vbucks'] = $novoSaldo;

    // Retorna sucesso para o JavaScript
    echo json_encode([
        'sucesso' => true, 
        'msg' => 'Compra realizada com sucesso!', 
        'novo_saldo' => $novoSaldo
    ]);

} catch (Exception $e) {
    // Se der qualquer erro no meio do caminho, desfaz as alterações
    $conn->rollback();
    echo json_encode(['sucesso' => false, 'msg' => 'Erro no servidor: ' . $e->getMessage()]);
}
?>