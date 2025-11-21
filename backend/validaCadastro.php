<?php
session_start();

// Tenta incluir a conexão (verifica se está na pasta backend ou raiz)
if (file_exists('../conecta.php')) {
    require '../conecta.php';
} elseif (file_exists('conecta.php')) {
    require 'conecta.php';
} else {
    die("Erro: Arquivo de conexão não encontrado.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $conn->real_escape_string($_POST['nome']);
    $email = $conn->real_escape_string($_POST['email']);
    $senha = $_POST['senha'];
    $confirmarSenha = $_POST['confirmaSenha']; // Certifique-se que o input name no HTML é "confirmaSenha"

    // 1. Validação de Senha
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

    // 3. Criptografa a senha
    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

    // 4. Insere no Banco (V-Bucks iniciais: 10000)
    $sql = "INSERT INTO usuarios (nome, email, senha, vbucks) VALUES (?, ?, ?, 10000)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $nome, $email, $senhaHash);

    if ($stmt->execute()) {
        // SUCESSO: Redireciona para o Login para a pessoa entrar
        // Opcional: Você pode criar uma sessão de sucesso para exibir no login se quiser
        $_SESSION['login_erro'] = "Cadastro realizado! Faça login."; // Usando a msg de erro do login só pra aparecer algo (vai aparecer vermelho, mas avisa)
        header("Location: ../paginas/login.php");
        exit;
    } else {
        // ERRO NO BANCO
        $_SESSION['cadastro_erro'] = "Erro ao cadastrar: " . $conn->error;
        header("Location: ../paginas/cadastro.php");
        exit;
    }
    $stmt->close();
} else {
    // Se tentar acessar direto
    header("Location: ../paginas/cadastro.php");
    exit;
}
?><?php
session_start();

// Tenta incluir a conexão (verifica se está na pasta backend ou raiz)
if (file_exists('../conecta.php')) {
    require '../conecta.php';
} elseif (file_exists('conecta.php')) {
    require 'conecta.php';
} else {
    die("Erro: Arquivo de conexão não encontrado.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $conn->real_escape_string($_POST['nome']);
    $email = $conn->real_escape_string($_POST['email']);
    $senha = $_POST['senha'];
    $confirmarSenha = $_POST['confirmaSenha']; // Certifique-se que o input name no HTML é "confirmaSenha"

    // 1. Validação de Senha
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

    // 3. Criptografa a senha
    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

    // 4. Insere no Banco (V-Bucks iniciais: 10000)
    $sql = "INSERT INTO usuarios (nome, email, senha, vbucks) VALUES (?, ?, ?, 10000)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $nome, $email, $senhaHash);

    if ($stmt->execute()) {
        // SUCESSO: Redireciona para o Login para a pessoa entrar
        // Opcional: Você pode criar uma sessão de sucesso para exibir no login se quiser
        $_SESSION['login_erro'] = "Cadastro realizado! Faça login."; // Usando a msg de erro do login só pra aparecer algo (vai aparecer vermelho, mas avisa)
        header("Location: ../paginas/login.php");
        exit;
    } else {
        // ERRO NO BANCO
        $_SESSION['cadastro_erro'] = "Erro ao cadastrar: " . $conn->error;
        header("Location: ../paginas/cadastro.php");
        exit;
    }
    $stmt->close();
} else {
    // Se tentar acessar direto
    header("Location: ../paginas/cadastro.php");
    exit;
}
?>