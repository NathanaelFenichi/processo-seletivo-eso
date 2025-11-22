<?php

$servername = getenv('MYSQLHOST') ?: "db"; 
$username   = getenv('MYSQLUSER') ?: "root";
$password   = getenv('MYSQLPASSWORD') ?: "root";
$dbname     = getenv('MYSQLDATABASE') ?: "mydb";
$port       = getenv('MYSQLPORT') ?: 3306;


$conn = mysqli_init();


$conn->ssl_set(NULL, NULL, NULL, NULL, NULL); 

try {

    $conn->real_connect($servername, $username, $password, $dbname, $port, NULL, MYSQLI_CLIENT_SSL);
} catch (Exception $e) {
    die(json_encode(['sucesso' => false, 'msg' => 'Erro na conexão SSL: ' . $e->getMessage()]));
}

// Verifica se conectou
if ($conn->connect_errno) {
    die(json_encode(['sucesso' => false, 'msg' => 'Falha DB: ' . $conn->connect_error]));
}

$conn->set_charset("utf8mb4");
?>