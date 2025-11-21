<?php
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['sucesso' => false, 'msg' => 'Você precisa estar logado para comprar!']);
    exit;
}

if (file_exists('../conecta.php')) {
    require '../conecta.php';
} elseif (file_exists('conecta.php')) {
    require 'conecta.php';
} else {
    echo json_encode(['sucesso' => false, 'msg' => 'Erro interno: Arquivo de conexão não encontrado.']);
    exit;
}

$userId = $_SESSION['user_id'];

$input = json_decode(file_get_contents('php://input'), true);
$itemId = $input['id'] ?? null;
$preco = $input['preco'] ?? 0;

if (!$itemId) {
    echo json_encode(['sucesso' => false, 'msg' => 'ID do item inválido.']);
    exit;
}

try {
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

    if ($saldoAtual < $preco) {
        echo json_encode(['sucesso' => false, 'msg' => 'V-Bucks insuficientes!']);
        exit;
    }

    $check = $conn->prepare("SELECT id FROM compras WHERE user_id = ? AND cosmetic_id = ?");
    $check->bind_param("is", $userId, $itemId);
    $check->execute();
    if ($check->get_result()->num_rows > 0) {
        echo json_encode(['sucesso' => false, 'msg' => 'Você já possui este item!']);
        exit;
    }

    $conn->begin_transaction();

    $novoSaldo = $saldoAtual - $preco;
    $update = $conn->prepare("UPDATE usuarios SET vbucks = ? WHERE ID = ?");
    $update->bind_param("ii", $novoSaldo, $userId);
    $update->execute();

    $insert = $conn->prepare("INSERT INTO compras (user_id, cosmetic_id, preco) VALUES (?, ?, ?)");
    $insert->bind_param("isi", $userId, $itemId, $preco);
    $insert->execute();

    $conn->commit();

    $_SESSION['vbucks'] = $novoSaldo;

    echo json_encode([
        'sucesso' => true,
        'msg' => 'Compra realizada com sucesso!',
        'novo_saldo' => $novoSaldo
    ]);

} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['sucesso' => false, 'msg' => 'Erro no servidor: ' . $e->getMessage()]);
}
?>
