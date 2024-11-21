<?php
session_start();
include 'db_connection.php';

// Verifica se o método de requisição é POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prevenir SQL Injection com prepared statements
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username); // 's' significa string
    $stmt->execute();
    $result = $stmt->get_result();

    // Verifica se o usuário foi encontrado
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Comparação direta da senha (sem hash)
        if ($password === $user['password']) {
            $_SESSION['username'] = $user['username'];
            $_SESSION['profile_pic'] = $user['profile_pic'];
            $_SESSION['role'] = $user['role'];

            // Atualiza o horário de login
            $updateLoginTime = "UPDATE users SET last_login = NOW() WHERE id = ?";
            $updateStmt = $conn->prepare($updateLoginTime);
            $updateStmt->bind_param("i", $user['id']); // 'i' significa inteiro
            $updateStmt->execute();

            header("Location: dashboard.php");
            exit();
        } else {
            // Senha incorreta
            echo "Senha incorreta!";
        }
    } else {
        // Usuário não encontrado
        echo "Usuário não encontrado!";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <form method="post" action="login.php">
    <label for="username">Usuário:</label>
    <input type="text" id="username" name="username" required>
    
    <label for="password">Senha:</label>
    <input type="password" id="password" name="password" required>
    
    <button type="submit">Entrar</button>
  </form>
</body>
</html>
