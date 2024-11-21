<?php
session_start();
include 'db_connection.php';

// Verifica se o usuário tem permissão de administrador
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: dashboard.php");
    exit();
}

// Processa o formulário ao receber uma requisição POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Sanitiza os inputs
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    $status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_STRING);

    // Verifica se os valores foram recebidos corretamente e se o status é válido
    $statusOptions = ['empty', 'full'];
    if ($id && in_array($status, $statusOptions)) {
        // Prepared statement para evitar SQL Injection
        $stmt = $conn->prepare("UPDATE trash_bins SET status = ?, edit_date = NOW() WHERE id = ?");
        $stmt->bind_param("si", $status, $id); // 's' para string (status) e 'i' para inteiro (id)
        
        if ($stmt->execute()) {
            // Redireciona para o painel com uma mensagem de sucesso
            header("Location: dashboard.php?success=1");
            exit();
        } else {
            error_log("Erro ao atualizar o status: " . $stmt->error);
            // Mensagem de erro mais detalhada
            $errorMessage = "Erro ao atualizar o status da lixeira. Tente novamente mais tarde.";
            echo $errorMessage;
        }
        
        $stmt->close();
    } else {
        // Mensagem mais informativa caso os dados sejam inválidos
        echo "Dados inválidos! Verifique se o ID e o status estão corretos.";
    }
}
?>
