<?php
require 'conecta.php';
session_start();

$erro = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Verifica se o email existe
    $stmt = $conn->prepare("SELECT id, nome, senha FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Verifica login sem revelar qual dado está errado
    if (!$user || !password_verify($senha, $user['senha'])) {
        $erro = 'Usuário ou senha inválidos!';
    }

    // Se não houver erro, cria a sessão
    if ($erro == '') {
        $_SESSION['usuario_id'] = $user['id'];
        $_SESSION['usuario_nome'] = $user['nome'];
        echo "<script>alert(;</script>";
        header("Location: ../index.php");
         
    
    } else {
        echo "<script>alert('$erro');history.back();</script>";
        exit;
    }
}
?>
