<?php
// O PHP vai tentar pegar os dados das "Variáveis de Ambiente" (da nuvem).
// Se não achar (ou seja, se estiver no seu PC), ele usa o padrão 'db', 'root', etc.

$servername = getenv('MYSQLHOST') ?: "db"; 
$username   = getenv('MYSQLUSER') ?: "root";
$password   = getenv('MYSQLPASSWORD') ?: "root";
$dbname     = getenv('MYSQLDATABASE') ?: "mydb";
$port       = getenv('MYSQLPORT') ?: 3306;

$conn = new mysqli($servername, $username, $password, $dbname, $port);

if ($conn->connect_error) {
    // Em produção, evite mostrar o erro exato para o usuário (segurança), mas para teste ok:
    die(json_encode(['sucesso' => false, 'msg' => 'Falha DB: ' . $conn->connect_error]));
}

$conn->set_charset("utf8mb4");
?>