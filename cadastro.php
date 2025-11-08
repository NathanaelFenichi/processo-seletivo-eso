<?php

require 'conecta.php';
if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $confirmarSenha = $_POST['confirmaSenha'];

    if ($senha !== $confirmarSenha){
        die("As senhas não coincidem");
    }

    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);


    $sql = "INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $nome, $email, $senhaHash);

    if ($stmt->execute()){
        echo "Cadastro realizado com sucesso!";
    } else {
        echo "Erro ao cadastrar: " . $stmt->error;
    }
}

?>