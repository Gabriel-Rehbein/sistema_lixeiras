<?php
session_start();
include 'db_connection.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];
$success_message = '';

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $new_username = trim($_POST['username']);
    $new_password = trim($_POST['password']);

    // Valida e sanitiza os dados de entrada
    if (empty($new_username)) {
        $error_message = "O nome de usuário não pode estar vazio.";
    } else {
        // Atualiza o nome de usuário
        $stmt = $conn->prepare("UPDATE users SET username = ? WHERE username = ?");
        $stmt->bind_param("ss", $new_username, $username);
        $stmt->execute();

        // Atualiza a senha se fornecida
        if (!empty($new_password)) {
            $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
            $stmt = $conn->prepare("UPDATE users SET password = ? WHERE username = ?");
            $stmt->bind_param("ss", $hashed_password, $new_username);
            $stmt->execute();
        }

        // Atualiza a sessão e exibe mensagem de sucesso
        $_SESSION['username'] = $new_username;
        $success_message = "Configurações atualizadas com sucesso!";
        $stmt->close();
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configurações da Conta</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Configurações da Conta</h1>
    </header>

    <main>
        <!-- Exibe mensagem de sucesso ou erro -->
        <?php if (!empty($success_message)): ?>
            <p class="success"><?php echo htmlspecialchars($success_message); ?></p>
        <?php elseif (!empty($error_message)): ?>
            <p class="error"><?php echo htmlspecialchars($error_message); ?></p>
        <?php endif; ?>

        <form method="post" action="">
            <label for="username">Nome de Usuário:</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" required>

            <label for="password">Nova Senha (opcional):</label>
            <input type="password" id="password" name="password">

            <button type="submit">Atualizar Configurações</button>
        </form>
        <p><a href="dashboard.php">Voltar ao Dashboard</a></p>
    </main>
</body>
</html>
