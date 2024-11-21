<?php
session_start();
include 'db_connection.php';

// Verifica se o usuário está logado e é administrador
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Consulta para obter todos os logs de atividade
$sql = "SELECT logs.id, users.username, logs.action, logs.timestamp 
        FROM activity_logs logs
        JOIN users ON logs.user_id = users.id
        ORDER BY logs.timestamp DESC";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logs de Atividades</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h2>Logs de Atividades</h2>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Usuário</th>
            <th>Ação</th>
            <th>Data</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['username']; ?></td>
                <td><?php echo $row['action']; ?></td>
                <td><?php echo $row['timestamp']; ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>

</body>
</html>
