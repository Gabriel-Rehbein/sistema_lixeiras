<?php
session_start();
include 'db_connection.php';

// Verifica se o usuário está logado e é administrador
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Verifica se o ID do usuário foi passado
if (!isset($_GET['id']) || !filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    echo "ID de usuário inválido!";
    exit();
}

$user_id = $_GET['id'];

// Exclui o usuário
$sql_delete = "DELETE FROM users WHERE id = ?";
$stmt = $conn->prepare($sql_delete);
$stmt->bind_param("i", $user_id);

if ($stmt->execute()) {
    $_SESSION['success'] = "Usuário excluído com sucesso!";
} else {
    $_SESSION['error'] = "Erro ao excluir o usuário!";
}

header("Location: dashboard.php");
exit();
?>
