<?php
session_start();
include 'db_connection.php';

// Verifica se o usuário está logado e é administrador
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Função para adicionar lixeira
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_bin'])) {
    $bin_name = htmlspecialchars($_POST['bin_name']);
    $status = $_POST['status'];
    $location = htmlspecialchars($_POST['location']);

    if (empty($bin_name) || empty($status) || empty($location)) {
        $_SESSION['error'] = "Todos os campos são obrigatórios!";
    } else {
        $sql = "INSERT INTO trash_bins (name, status, location) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $bin_name, $status, $location);
        if ($stmt->execute()) {
            $_SESSION['success'] = "Lixeira adicionada com sucesso!";
        } else {
            $_SESSION['error'] = "Erro ao adicionar lixeira!";
        }
    }
}

// Obtém o histórico das lixeiras
$sql = "SELECT * FROM trash_bins";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Lixeiras</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h2>Gerenciar Lixeiras</h2>

<?php
// Exibe mensagens de feedback
if (isset($_SESSION['success'])) {
    echo "<p class='success'>{$_SESSION['success']}</p>";
    unset($_SESSION['success']);
}

if (isset($_SESSION['error'])) {
    echo "<p class='error'>{$_SESSION['error']}</p>";
    unset($_SESSION['error']);
}
?>

<form method="POST">
    <label for="bin_name">Nome da Lixeira:</label>
    <input type="text" id="bin_name" name="bin_name" required>

    <label for="status">Status:</label>
    <select id="status" name="status">
        <option value="empty">Vazia</option>
        <option value="full">Cheia</option>
    </select>

    <label for="location">Localização:</label>
    <input type="text" id="location" name="location" required>

    <button type="submit" name="add_bin">Adicionar Lixeira</button>
</form>

<h3>Histórico das Lixeiras</h3>
<table>
    <tr>
        <th>ID</th>
        <th>Nome</th>
        <th>Status</th>
        <th>Localização</th>
        <th>Ações</th>
    </tr>

    <?php while ($row = $result->fetch_assoc()) : ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['name']; ?></td>
            <td><?php echo $row['status']; ?></td>
            <td><?php echo $row['location']; ?></td>
            <td><a href="edit_bin.php?id=<?php echo $row['id']; ?>">Editar</a> | <a href="delete_bin.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Tem certeza que deseja excluir?');">Excluir</a></td>
        </tr>
    <?php endwhile; ?>
</table>

</body>
</html>
