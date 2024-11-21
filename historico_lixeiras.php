<?php
session_start();
include 'db_connection.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Verifica se o usuário é administrador
$isAdmin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';

// Consulta para obter as lixeiras com verificação de erro
$query = "SELECT * FROM trash_bins ORDER BY edit_date DESC";
$result = $conn->query($query);

// Verifica se a consulta retornou algum resultado
if ($result === false) {
    echo "<p>Erro ao carregar as lixeiras.</p>";
    exit();
}

if ($result->num_rows > 0):
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Histórico das Lixeiras</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <h1>Histórico das Lixeiras</h1>
    <p>Bem-vindo, <?php echo htmlspecialchars($_SESSION['username']); ?></p>
</header>

<main>
    <section class="trash-history">
        <h2>Lista de Lixeiras</h2>

        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="trash-card">
                <img src="<?php echo htmlspecialchars($row['photo']); ?>" alt="Foto da Lixeira">
                <div class="trash-info">
                    <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                    <p>Localização: <?php echo htmlspecialchars($row['location']); ?></p>
                    <p>Data de Edição: <?php echo htmlspecialchars($row['edit_date']); ?></p>
                    <p>Status: 
                        <span class="status-icon" aria-label="<?php echo $row['status'] == 'empty' ? 'Vazia' : 'Cheia'; ?>">
                            <?php echo $row['status'] == 'empty' ? '🟢' : '🔴'; ?>
                        </span>
                    </p>
                </div>

                <?php if ($isAdmin): ?>
                    <!-- Exibir opções de edição e exclusão apenas para administradores -->
                    <div class="admin-controls">
                        <button onclick="location.href='edit_trash.php?id=<?php echo $row['id']; ?>'">Editar</button>
                        <button onclick="location.href='delete_trash.php?id=<?php echo $row['id']; ?>'">Excluir</button>
                    </div>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    </section>
</main>

<footer>
    <p>&copy; <?php echo date("Y"); ?> Lixeiras Inteligentes. Todos os direitos reservados.</p>
    <div class="footer-links">
        <a href="about.php">Sobre</a>
        <a href="privacy.php">Privacidade</a>
        <a href="terms.php">Termos de Serviço</a>
    </div>
    <p><a href="logout.php">Sair</a></p>
</footer>

</body>
</html>

<?php
else:
    echo "<p>Não há lixeiras registradas no sistema.</p>";
endif;

$conn->close();
?>
