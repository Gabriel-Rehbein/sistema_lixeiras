<?php
session_start();
include 'db_connection.php';

// Verifica se o usuário está logado e é administrador
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Verifica se o ID da lixeira foi passado
if (!isset($_GET['id']) || !filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    echo "ID de lixeira inválido!";
    exit();
}

$bin_id = $_GET['id'];

// Exclui a lixeira
$sql_delete = "DELETE FROM trash_bins WHERE id = ?";
$stmt = $conn->prepare($sql_delete);
$stmt->bind_param("i", $bin_id);

if ($stmt->execute()) {
    $_SESSION['success'] = "Lixeira excluída com sucesso!";
} else {
    $_SESSION['error'] = "Erro ao excluir lixeira!";
}

header("Location: manage_bins.php");
exit();
?>
