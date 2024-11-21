<?php
session_start();
include 'db_connection.php';

// Verifica se o usuário está logado e é administrador
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Obtém os dados de lixeiras
$sql = "SELECT * FROM trash_bins";
$result = $conn->query($sql);

// Gera um relatório simples
$full_bins = 0;
$empty_bins = 0;

while ($row = $result->fetch_assoc()) {
    if ($row['status'] == 'full') {
        $full_bins++;
    } else {
        $empty_bins++;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatórios</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h2>Relatório de Lixeiras</h2>

<p>Total de lixeiras vazias: <?php echo $empty_bins; ?></p>
<p>Total de lixeiras cheias: <?php echo $full_bins; ?></p>

</body>
</html>
