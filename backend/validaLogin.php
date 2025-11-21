<?php
session_start();

// Tenta incluir a conexão. Se falhar, para tudo.
// Verifica se está na pasta backend (../conecta.php) ou na raiz (conecta.php)
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

    // Busca o usuário pelo email
    $sql = "SELECT * FROM usuarios WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Verifica a senha
        // Se estiver usando senhas criptografadas com password_hash (Recomendado)
        if (password_verify($senha, $user['senha'])) {
            
            // CRIA A SESSÃO COMPLETA
            $_SESSION['id_sistema'] = 'sislogin2024*'; // Seu token de controle interno
            $_SESSION['user_id'] = $user['ID'];        // ID para o banco
            $_SESSION['nome'] = $user['nome'];         // Nome para exibir na Nav
            $_SESSION['vbucks'] = $user['vbucks'];     // Saldo para exibir na Nav
            
            // Redireciona para a página principal (Home)
            header("Location: ../index.php"); 
            exit;
        }
        
    }
    
    // Login Falhou (Email não existe ou senha errada)
    $_SESSION['login_erro'] = "Email ou senha incorretos!";
    header("Location: ../paginas/login.php"); 
    exit;
} else {
    // Se tentar acessar direto sem POST, joga pro login
    header("Location: ../paginas/login.php");
    exit;
}
?>