<?php
session_start();

if (file_exists('../conecta.php')) {
    require '../conecta.php';
} elseif (file_exists('conecta.php')) {
    require 'conecta.php';
} else {
    die("Erro: Arquivo de conexão não encontrado.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $conn->real_escape_string($_POST['email']);
    $senha = $_POST['senha'];

    $sql = "SELECT * FROM usuarios WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($senha, $user['senha'])) {
            $_SESSION['id_sistema'] = 'sislogin2024*';
            $_SESSION['user_id'] = $user['ID'];
            $_SESSION['nome'] = $user['nome'];
            $_SESSION['vbucks'] = $user['vbucks'];

            header("Location: ../index.php");
            exit;
        }
    }

    $_SESSION['login_erro'] = "Email ou senha incorretos!";
    header("Location: ../paginas/login.php");
    exit;
} else {
    header("Location: ../paginas/login.php");
    exit;
}
?>
