<?php
$servername = "localhost";
$username = "root";
$password = ""; // Sua senha do XAMPP (geralmente vazia)
$dbname = "mydb"; // Nome do seu banco

// Cria a conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Checa a conexão
if ($conn->connect_error) {
    // Mata o script com erro JSON para o JS entender
    die(json_encode(['sucesso' => false, 'msg' => 'Falha na conexão DB: ' . $conn->connect_error]));
}

// Define charset para evitar problemas com acentuação
$conn->set_charset("utf8mb4");

// NÃO FECHE A TAG PHP AQUI NO FINAL (EVITA ERROS DE ESPAÇO EM BRANCO)