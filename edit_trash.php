<?php
session_start();
include 'db_connection.php';

// Verifica se o usuário está logado e é administrador
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Verifica se o ID da lixeira foi passado na URL e se é um número válido
if (!isset($_GET['id']) || !filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    echo "ID da lixeira não especificado ou inválido.";
    exit();
}

// Obtém os dados da lixeira para edição
$trash_id = $_GET['id'];
$sql = "SELECT * FROM trash_bins WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $trash_id);
$stmt->execute();
$result = $stmt->get_result();

// Verifica se a lixeira existe
if ($result->num_rows === 0) {
    echo "Lixeira não encontrada.";
    exit();
}

$trash = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtém e sanitiza os dados do formulário
    $name = htmlspecialchars($_POST['name']);
    $location = htmlspecialchars($_POST['location']);
    $status = htmlspecialchars($_POST['status']);
    
    // Atualiza as informações da lixeira no banco de dados
    $sql_update = "UPDATE trash_bins SET name = ?, location = ?, status = ?, edit_date = NOW() WHERE id = ?";
    $stmt = $conn->prepare($sql_update);
    $stmt->bind_param("sssi", $name, $location, $status, $trash_id);
    
    // Verifica se a execução foi bem-sucedida
    if ($stmt->execute()) {
        $_SESSION['message'] = "Lixeira atualizada com sucesso.";
    } else {
        $_SESSION['error'] = "Erro ao atualizar a lixeira: " . $stmt->error;
    }

    header("Location: dashboard.php");
    exit();
}

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
    // Exibe mensagens de sucesso ou erro, se houver
    if (isset($_SESSION['message'])) {
        echo "<p class='success'>{$_SESSION['message']}</p>";
        unset($_SESSION['message']);
    }

    if (isset($_SESSION['error'])) {
        echo "<p class='error'>{$_SESSION['error']}</p>";
        unset($_SESSION['error']);
    }
    ?>

    <form method="post">
        <label for="name">Nome:</label>
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($trash['name']); ?>" required>
        
        <label for="location">Localização:</label>
        <input type="text" id="location" name="location" value="<?php echo htmlspecialchars($trash['location']); ?>" required>
        
        <label for="status">Status:</label>
        <select id="status" name="status">
            <option value="empty" <?php echo ($trash['status'] == 'empty') ? 'selected' : ''; ?>>Vazia</option>
            <option value="full" <?php echo ($trash['status'] == 'full') ? 'selected' : ''; ?>>Cheia</option>
        </select>
        
        <button type="submit">Salvar Alterações</button>
    </form>

    <p><a href="dashboard.php">Voltar ao Dashboard</a></p>
</body>
</html>
