<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mydb";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(['sucesso' => false, 'msg' => 'Falha na conexão DB: ' . $conn->connect_error]));
}

$conn->set_charset("utf8mb4");
