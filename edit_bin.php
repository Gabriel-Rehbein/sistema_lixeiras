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

// Obtém os dados da lixeira
$sql = "SELECT * FROM trash_bins WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $bin_id);
$stmt->execute();
$result = $stmt->get_result();
$bin = $result->fetch_assoc();

// Verifica se a lixeira foi encontrada
if (!$bin) {
    echo "Lixeira não encontrada!";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $bin_name = htmlspecialchars($_POST['bin_name']);
    $status = $_POST['status'];
    $location = htmlspecialchars($_POST['location']);

    // Validação simples
    if (empty($bin_name) || empty($status) || empty($location)) {
        $_SESSION['error'] = "Todos os campos são obrigatórios!";
    } else {
        $sql_update = "UPDATE trash_bins SET name = ?, status = ?, location = ? WHERE id = ?";
        $stmt = $conn->prepare($sql_update);
        $stmt->bind_param("sssi", $bin_name, $status, $location, $bin_id);
        if ($stmt->execute()) {
            $_SESSION['success'] = "Lixeira atualizada com sucesso!";
        } else {
            $_SESSION['error'] = "Erro ao atualizar lixeira!";
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Lixeira</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h2>Editar Lixeira</h2>

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

<form method="POST">
    <label for="bin_name">Nome da Lixeira:</label>
    <input type="text" id="bin_name" name="bin_name" value="<?php echo htmlspecialchars($bin['name']); ?>" required>

    <label for="status">Status:</label>
    <select id="status" name="status">
        <option value="empty" <?php echo ($bin['status'] == 'empty') ? 'selected' : ''; ?>>Vazia</option>
        <option value="full" <?php echo ($bin['status'] == 'full') ? 'selected' : ''; ?>>Cheia</option>
    </select>

    <label for="location">Localização:</label>
    <input type="text" id="location" name="location" value="<?php echo htmlspecialchars($bin['location']); ?>" required>

    <button type="submit">Salvar Alterações</button>
</form>

</body>
</html>
