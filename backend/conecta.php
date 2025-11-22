<?php
// Habilita o modo de relatório de erros para pegar falhas do banco
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    // 1. Pega as credenciais das variáveis de ambiente (do Render)
    $servername = getenv('MYSQLHOST') ?: "db"; 
    $username   = getenv('MYSQLUSER') ?: "root";
    $password   = getenv('MYSQLPASSWORD') ?: "root";
    $dbname     = getenv('MYSQLDATABASE') ?: "mydb";
    $port       = getenv('MYSQLPORT') ?: 3306;

    // 2. Inicializa o driver MySQLi
    $conn = mysqli_init();
    if (!$conn) {
        throw new Exception("Falha ao inicializar mysqli");
    }

    // 3. Configuração OBRIGATÓRIA para o TiDB Cloud (SSL)
    // Define os certificados como NULL para usar o padrão do sistema, mas ativa o modo seguro
    $conn->ssl_set(NULL, NULL, NULL, NULL, NULL);
    
    // Desativa verificação rigorosa de certificado (ajuda a evitar erros em containers Docker)
    $conn->options(MYSQLI_OPT_SSL_VERIFY_SERVER_CERT, false);

    // 4. Conecta usando a flag MYSQLI_CLIENT_SSL
    $conectou = $conn->real_connect($servername, $username, $password, $dbname, (int)$port, NULL, MYSQLI_CLIENT_SSL);

    if (!$conectou) {
         throw new Exception("Erro ao conectar: " . $conn->connect_error);
    }

    // Define o charset para aceitar acentos
    $conn->set_charset("utf8mb4");

} catch (Throwable $e) {
    // Se der erro, para a execução e mostra a mensagem.
    // Não usamos JSON aqui para não quebrar as páginas HTML (usuarios.php) se der erro.
    die("Erro Crítico de Conexão: " . $e->getMessage());
}
?>