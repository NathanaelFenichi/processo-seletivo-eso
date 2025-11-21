<?php
session_start();
require 'conecta.php'; // Usa sua conexão existente

header('Content-Type: application/json');

// 1. Verifica se usuário está logado
if (!isset($_SESSION['id_usuario'])) {
    // Para teste rápido, se não tiver login, vamos pegar o primeiro usuário do banco
    // REMOVA ISSO EM PRODUÇÃO, é só para o seu teste funcionar agora!
    $result = $conn->query("SELECT ID FROM usuarios LIMIT 1");
    if ($row = $result->fetch_assoc()) {
        $_SESSION['id_usuario'] = $row['ID'];
    } else {
        echo json_encode(['sucesso' => false, 'msg' => 'Nenhum usuário encontrado ou logado.']);
        exit;
    }
}

$userId = $_SESSION['id_usuario'];

// 2. Recebe dados do JavaScript
$input = json_decode(file_get_contents('php://input'), true);
$itemId = $input['id'] ?? null;
$preco = $input['preco'] ?? 0;

if (!$itemId) {
    echo json_encode(['sucesso' => false, 'msg' => 'ID do item inválido.']);
    exit;
}

// 3. Lógica da Compra
try {
    // A. Verifica Saldo
    $stmt = $conn->prepare("SELECT vbucks FROM usuarios WHERE ID = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $res = $stmt->get_result();
    $user = $res->fetch_assoc();
    $saldoAtual = $user['vbucks'];

    if ($saldoAtual < $preco) {
        echo json_encode(['sucesso' => false, 'msg' => 'V-Bucks insuficientes!']);
        exit;
    }

    // B. Verifica se já comprou (Opcional)
    $check = $conn->prepare("SELECT id FROM compras WHERE user_id = ? AND cosmetic_id = ?");
    $check->bind_param("is", $userId, $itemId);
    $check->execute();
    if ($check->get_result()->num_rows > 0) {
        echo json_encode(['sucesso' => false, 'msg' => 'Você já tem este item!']);
        exit;
    }

    // C. Realiza a Compra (Transação manual)
    $conn->begin_transaction();

    // Desconta V-Bucks
    $novoSaldo = $saldoAtual - $preco;
    $update = $conn->prepare("UPDATE usuarios SET vbucks = ? WHERE ID = ?");
    $update->bind_param("ii", $novoSaldo, $userId);
    $update->execute();

    // Registra na tabela compras
    $insert = $conn->prepare("INSERT INTO compras (user_id, cosmetic_id, preco) VALUES (?, ?, ?)");
    $insert->bind_param("isi", $userId, $itemId, $preco);
    $insert->execute();

    $conn->commit();

    // Atualiza saldo na sessão
    $_SESSION['vbucks'] = $novoSaldo;

    echo json_encode(['sucesso' => true, 'msg' => 'Compra realizada com sucesso!', 'novo_saldo' => $novoSaldo]);

} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['sucesso' => false, 'msg' => 'Erro no servidor: ' . $e->getMessage()]);
}
?>