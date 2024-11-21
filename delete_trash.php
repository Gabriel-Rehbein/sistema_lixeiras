<?php
session_start();
include 'db_connection.php';

// Verifica se o usuário é administrador
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: dashboard.php");
    exit();
}

// Verifica se o ID da lixeira foi passado na URL e se é um número válido
if (!isset($_GET['id']) || !filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    echo "ID da lixeira não especificado ou inválido.";
    exit();
}

$trash_id = intval($_GET['id']);

try {
    // Prepara e executa a consulta de exclusão
    $stmt = $conn->prepare("DELETE FROM trash_bins WHERE id = ?");
    $stmt->bind_param("i", $trash_id);

    if ($stmt->execute()) {
        // Mensagem de sucesso (pode ser substituída por um redirecionamento ou notificação mais amigável)
        $_SESSION['message'] = "Lixeira excluída com sucesso.";
    } else {
        $_SESSION['error'] = "Erro ao excluir a lixeira: " . $stmt->error;
    }
    $stmt->close();
} catch (Exception $e) {
    $_SESSION['error'] = "Erro ao excluir a lixeira: " . $e->getMessage();
}

// Fecha a conexão
$conn->close();

// Redireciona de volta ao dashboard
header("Location: dashboard.php");
exit();
?>
