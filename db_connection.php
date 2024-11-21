<?php
$servername = "localhost";  // ou o IP do servidor de banco de dados
$username = "root";         // seu usuário do banco de dados
$password = "";             // senha do banco de dados
$dbname = "smart_trash";         // nome do banco de dados

// Ativa o relatório de erros
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    // Criação da conexão
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    // Verifica a conexão
    if ($conn->connect_error) {
        throw new Exception("Falha na conexão: " . $conn->connect_error);
    }
    
    // Caso a conexão seja bem-sucedida, não é necessário echo aqui para produção
    // echo "Conexão com o banco de dados bem-sucedida!";
} catch (Exception $e) {
    // Exibe uma mensagem genérica para o usuário e registra o erro no log
    error_log($e->getMessage()); // Loga o erro no servidor
    die("Não foi possível conectar ao banco de dados no momento. Tente novamente mais tarde.");
}
?>
