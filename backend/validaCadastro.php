<?php
session_start();

// Verifica se o arquivo de conexão existe na pasta anterior ou na atual
if (file_exists('../conecta.php')) {
    require '../conecta.php';
} elseif (file_exists('conecta.php')) {
    require 'conecta.php';
} else {
    die("Erro: Arquivo de conexão não encontrado.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitização básica
    $nome = $conn->real_escape_string($_POST['nome']);
    $email = $conn->real_escape_string($_POST['email']);
    $senha = $_POST['senha'];
    $confirmarSenha = $_POST['confirmaSenha'];

    // 1. Verifica se as senhas batem
    if ($senha !== $confirmarSenha) {
        $_SESSION['cadastro_erro'] = "As senhas não coincidem.";
        header("Location: ../paginas/cadastro.php");
        exit;
    }

    // 2. Verifica se o email já existe
    $check = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $_SESSION['cadastro_erro'] = "Esse email já está cadastrado!";
        header("Location: ../paginas/cadastro.php");
        exit;
    }
    $check->close();

    // 3. Criptografa a senha e insere no banco
    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

    // Insere usuário com 10.000 V-Bucks iniciais
    $sql = "INSERT INTO usuarios (nome, email, senha, vbucks) VALUES (?, ?, ?, 10000)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $nome, $email, $senhaHash);

    if ($stmt->execute()) {
        $_SESSION['login_erro'] = "Cadastro realizado! Faça login.";
        header("Location: ../paginas/login.php");
        exit;
    } else {
        $_SESSION['cadastro_erro'] = "Erro ao cadastrar: " . $conn->error;
        header("Location: ../paginas/cadastro.php");
        exit;
    }
    $stmt->close();
} else {
    // Se tentar acessar o arquivo diretamente sem ser via POST
    header("Location: ../paginas/cadastro.php");
    exit;
}
?>