<?php
session_start();

// Tenta incluir a conexão. Se falhar, para tudo.
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

    // Busca o usuário
    $sql = "SELECT * FROM usuarios WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Verifica a senha
        // NOTA: Se você cadastrou senhas sem hash (texto puro) para testar, use a linha comentada abaixo
        // if ($senha === $user['senha']) { 
        if (password_verify($senha, $user['senha'])) {
            
            // CRIA A SESSÃO COMPLETA
            $_SESSION['id_sistema'] = 'sislogin2024*'; // Seu token de controle
            $_SESSION['user_id'] = $user['ID'];        // ID para o banco (importante pro perfil/compra)
            $_SESSION['nome'] = $user['nome'];         // Nome para exibir na Nav
            $_SESSION['vbucks'] = $user['vbucks'];     // Saldo para exibir na Nav
            
            // Redireciona para a página principal (Home/Catálogo)
            // Ajuste aqui se sua página principal for index.php na raiz
            header("Location: ../index.php"); 
            exit;
        }
    }
    
    // Login Falhou
    $_SESSION['login_erro'] = "Email ou senha incorretos!";
    header("Location: ../paginas/login.php"); 
    exit;
}
?>