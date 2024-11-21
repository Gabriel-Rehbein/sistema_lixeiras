<?php
include 'db_connection.php';

// Verifica se o parâmetro "ip" foi enviado via GET
if (isset($_GET['ip'])) {
    // Sanitiza e valida o IP recebido
    $ip = filter_var($_GET['ip'], FILTER_VALIDATE_IP);

    // Verifica se o IP é válido
    if ($ip === false) {
        echo "IP inválido fornecido!";
    } else {
        // Prepared statement para evitar SQL Injection e atualiza o status para 'full'
        $stmt = $conn->prepare("UPDATE trash_bins SET status = 'full', edit_date = NOW() WHERE location = ?");
        $stmt->bind_param("s", $ip);

        if ($stmt->execute()) {
            echo "Status da lixeira atualizado com sucesso!";
        } else {
            error_log("Erro ao atualizar o status da lixeira: " . $stmt->error);
            echo "Erro ao atualizar o status da lixeira!";
        }

        $stmt->close();
    }
} else {
    echo "Parâmetro IP não fornecido!";
}

$conn->close();
?>
