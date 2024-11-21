<?php
session_start();
include 'db_connection.php';

// Verifica se o usuário é administrador
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: dashboard.php");
    exit();
}

// Inicializa as mensagens
$messages = [];

// Processa o formulário ao receber uma requisição POST para atualizar as senhas
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Define as senhas padrão para cada instituição
    $institutionsPasswords = [
        'Unisinos' => 'unisinos123',
        'PUCRS'    => 'pucrs123',
        'SenacRS'  => 'senac123',
        'UFRGS'    => 'ufrgs123'
    ];

    foreach ($institutionsPasswords as $institution => $plainPassword) {
        // Hash da senha usando bcrypt
        $hashedPassword = password_hash($plainPassword, PASSWORD_BCRYPT);

        // Atualiza a senha no banco de dados para todos os usuários da instituição correspondente
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE institution = ?");
        $stmt->bind_param("ss", $hashedPassword, $institution);

        if ($stmt->execute()) {
            $messages[] = "Senha para a instituição $institution atualizada com sucesso!";
        } else {
            $messages[] = "Erro ao atualizar a senha para a instituição $institution: " . $stmt->error;
        }

        $stmt->close();
    }
}

// Fecha a conexão com o banco de dados
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel do Administrador - Atualizar Senhas</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Atualizar Senhas das Instituições</h1>
    <p>Esta ação atualizará as senhas para os valores padrão definidos.</p>

    <!-- Exibe as mensagens de sucesso ou erro -->
    <?php if (!empty($messages)): ?>
        <div class="messages">
            <?php foreach ($messages as $message): ?>
                <p><?php echo htmlspecialchars($message); ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- Formulário para o administrador executar a atualização das senhas -->
    <form method="post" action="">
        <button type="submit" onclick="return confirm('Tem certeza de que deseja atualizar as senhas para os valores padrão?');">Atualizar Senhas</button>
    </form>
</body>
</html>
