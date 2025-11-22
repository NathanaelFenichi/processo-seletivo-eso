<?php
// Garante que o PHP avise o navegador que a resposta é JSON
header('Content-Type: application/json'); 

// Habilita modo de exceção para capturar erros do banco
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    // Pega as variáveis de ambiente
    $servername = getenv('MYSQLHOST') ?: "db"; 
    $username   = getenv('MYSQLUSER') ?: "root";
    $password   = getenv('MYSQLPASSWORD') ?: "root";
    $dbname     = getenv('MYSQLDATABASE') ?: "mydb";
    $port       = getenv('MYSQLPORT') ?: 3306;

    // Inicializa o MySQLi
    $conn = mysqli_init();
    if (!$conn) {
        throw new Exception("Falha ao inicializar mysqli");
    }

    // Configuração CRÍTICA para o TiDB Cloud:
    // 1. Define SSL (mesmo com certificados NULL, ativa o modo seguro)
    $conn->ssl_set(NULL, NULL, NULL, NULL, NULL);
    
    // 2. Tenta conectar ignorando verificação rigorosa de certificado (ajuda em alguns containers)
    $conn->options(MYSQLI_OPT_SSL_VERIFY_SERVER_CERT, false);

    // Conecta usando a flag de SSL Client
    $conectou = $conn->real_connect($servername, $username, $password, $dbname, (int)$port, NULL, MYSQLI_CLIENT_SSL);

    if (!$conectou) {
         throw new Exception("Erro ao conectar: " . $conn->connect_error);
    }

    $conn->set_charset("utf8mb4");

} catch (Throwable $e) {
    // Se der QUALQUER erro, devolve um JSON limpo para o frontend ler
    // O 'Throwable' pega tanto Erros Fatais quanto Exceptions
    echo json_encode([
        'sucesso' => false, 
        'msg' => 'Erro no Servidor: ' . $e->getMessage()
    ]);
    exit; // Para o script aqui para não imprimir mais nada
}
?>