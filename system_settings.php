<?php
session_start();
include 'db_connection.php';

// Verifica se o usuário está logado e é administrador
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Consulta as configurações do sistema
$sql = "SELECT * FROM system_settings";
$result = $conn->query($sql);

if (!$result) {
    die("Erro ao consultar as configurações: " . $conn->error);
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configurações do Sistema</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h2>Configurações do Sistema</h2>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nome da Configuração</th>
            <th>Valor</th>
            <th>Última Atualização</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo htmlspecialchars($row['id']); ?></td>
                <td><?php echo htmlspecialchars($row['setting_name']); ?></td>
                <td><?php echo htmlspecialchars($row['setting_value']); ?></td>
                <td><?php echo htmlspecialchars($row['updated_at']); ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>

</body>
</html>
