<?php
session_start();
include 'db_connection.php';

// Verifica se o usuário está logado e é administrador
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Verifica se o ID do usuário é válido
if (!isset($_GET['id']) || !filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    echo "ID de usuário inválido!";
    exit();
}

// Obtém o ID do usuário para edição
$user_id = (int) $_GET['id']; // Garantir que o ID seja um número inteiro
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Verifica se o usuário foi encontrado
if (!$user) {
    echo "Usuário não encontrado!";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verifica se o formulário é para edição ou exclusão
    if (isset($_POST['update'])) {
        // Sanitiza e valida os dados
        $username = htmlspecialchars($_POST['username']);
        $role = htmlspecialchars($_POST['role']);
        
        if (empty($username) || !in_array($role, ['user', 'admin'])) {
            $_SESSION['error'] = "Dados inválidos!";
        } else {
            $sql_update = "UPDATE users SET username = ?, role = ? WHERE id = ?";
            $stmt = $conn->prepare($sql_update);
            $stmt->bind_param("ssi", $username, $role, $user_id);
            if ($stmt->execute()) {
                $_SESSION['success'] = "Usuário atualizado com sucesso!";
            } else {
                $_SESSION['error'] = "Erro ao atualizar o usuário!";
            }
        }
    } elseif (isset($_POST['delete'])) {
        // Exclui o usuário
        $sql_delete = "DELETE FROM users WHERE id = ?";
        $stmt = $conn->prepare($sql_delete);
        $stmt->bind_param("i", $user_id);
        if ($stmt->execute()) {
            $_SESSION['success'] = "Usuário excluído com sucesso!";
        } else {
            $_SESSION['error'] = "Erro ao excluir o usuário!";
        }
    }

    // Redireciona de volta ao dashboard com feedback
    header("Location: dashboard.php");
    exit();
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuário</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Editar Usuário</h2>

    <?php
    // Exibe mensagens de feedback se houver
    if (isset($_SESSION['success'])) {
        echo "<p class='success'>{$_SESSION['success']}</p>";
        unset($_SESSION['success']);
    }

    if (isset($_SESSION['error'])) {
        echo "<p class='error'>{$_SESSION['error']}</p>";
        unset($_SESSION['error']);
    }
    ?>

    <form method="post">
        <label for="username">Nome de Usuário:</label>
        <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
        
        <label for="role">Função:</label>
        <select id="role" name="role">
            <option value="user" <?php echo ($user['role'] == 'user') ? 'selected' : ''; ?>>Usuário</option>
            <option value="admin" <?php echo ($user['role'] == 'admin') ? 'selected' : ''; ?>>Administrador</option>
        </select>
        
        <button type="submit" name="update">Salvar Alterações</button>
        <button type="submit" name="delete" onclick="return confirm('Tem certeza de que deseja excluir este usuário?');">Excluir Usuário</button>
    </form>
</body>
</html>
