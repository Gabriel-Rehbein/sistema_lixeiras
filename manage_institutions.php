<?php
session_start();
include 'db_connection.php';

// Verifica se o usuário está logado e é administrador
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Verifica se a instituição está sendo adicionada
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_institution'])) {
    $institution_name = htmlspecialchars($_POST['institution_name']);
    $institution_address = htmlspecialchars($_POST['institution_address']);

    if (empty($institution_name) || empty($institution_address)) {
        $_SESSION['error'] = "Todos os campos são obrigatórios!";
    } else {
        $sql = "INSERT INTO institutions (name, address) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $institution_name, $institution_address);
        if ($stmt->execute()) {
            $_SESSION['success'] = "Instituição adicionada com sucesso!";
        } else {
            $_SESSION['error'] = "Erro ao adicionar instituição!";
        }
    }
}

// Obtém as instituições cadastradas
$sql = "SELECT id, name, address FROM institutions";
$result = $conn->query($sql);

// Fecha a conexão
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Instituições</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h2>Gerenciar Instituições</h2>

<?php
if (isset($_SESSION['success'])) {
    echo "<p class='success'>{$_SESSION['success']}</p>";
    unset($_SESSION['success']);
}

if (isset($_SESSION['error'])) {
    echo "<p class='error'>{$_SESSION['error']}</p>";
    unset($_SESSION['error']);
}
?>

<h3>Adicionar Nova Instituição</h3>
<form method="POST">
    <label for="institution_name">Nome da Instituição:</label>
    <input type="text" id="institution_name" name="institution_name" required>

    <label for="institution_address">Endereço da Instituição:</label>
    <input type="text" id="institution_address" name="institution_address" required>

    <button type="submit" name="add_institution">Adicionar Instituição</button>
</form>

<h3>Instituições Cadastradas</h3>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Endereço</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo htmlspecialchars($row['id']); ?></td>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td><?php echo isset($row['address']) ? htmlspecialchars($row['address']) : 'Endereço não cadastrado'; ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>

</body>
</html>
