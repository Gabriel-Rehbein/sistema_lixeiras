<?php
session_start();
include 'db_connection.php';

// Verifica se o usuário está logado e possui um papel definido na sessão
if (!isset($_SESSION['role'])) {
    header("Location: login.php");
    exit();
}

// Recupera as informações do usuário da sessão com verificações adicionais
$username = isset($_SESSION['username']) ? $_SESSION['username'] : '';
$role = isset($_SESSION['role']) ? $_SESSION['role'] : '';
$institution = isset($_SESSION['institution']) ? $_SESSION['institution'] : 'Instituição não definida';
$last_login = isset($_SESSION['last_login']) ? $_SESSION['last_login'] : 'Último login não disponível';
$profile_pic = isset($_SESSION['profile_pic']) ? $_SESSION['profile_pic'] : 'default_profile_pic.jpg'; // Valor padrão para a foto

// Cabeçalho HTML
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard de Lixeiras</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <h1><?php echo $role === 'admin' ? 'Administrador' : 'Bem-vindo'; ?>, <?php echo htmlspecialchars($username); ?></h1>
    <img src="<?php echo htmlspecialchars($profile_pic); ?>" alt="Foto de Perfil" class="profile-pic">
    <p>Instituição: <?php echo htmlspecialchars($institution); ?></p>
    <p>Último login: <?php echo htmlspecialchars($last_login); ?></p>
</header>

<main>
    <!-- Exibir link para a página de administração, visível apenas para administradores -->
    <?php if ($role === 'admin'): ?>
        <section>
            <h2>Administração</h2>
            <p><a href="senhas.php">Atualizar Senhas das Instituições</a></p>
            <p><a href="edit_user.php">Gerenciar Usuários</a></p>
            <p><a href="create_user.php">Criar Novo Usuário</a></p>
            <p><a href="manage_bins.php">Gerenciar Lixeiras</a></p>
            <p><a href="activity_logs.php">Visualizar Logs de Atividades</a></p>
            <p><a href="system_settings.php">Configurações do Sistema</a></p>
            <p><a href="manage_institutions.php">Gerenciar Instituições</a></p>
            <p><a href="reports.php">Gerar Relatórios</a></p>
        </section>
    <?php endif; ?>

    <section>
        <h2>Outras Funcionalidades</h2>
        <!-- Links adicionais de funcionalidades -->
        <p><a href="historico_lixeiras.php">Visualizar Histórico das Lixeiras</a></p>
        <p><a href="configuracoes.php">Configurações da Conta</a></p>
    </section>
</main>

<!-- Rodapé -->
<footer>
    <div class="footer-content">
        <p>&copy; <?php echo date("Y"); ?> Lixeiras Inteligentes. Todos os direitos reservados.</p>
        <div class="footer-links">
            <a href="about.php">Sobre</a>
            <a href="privacy.php">Privacidade</a>
            <a href="terms.php">Termos de Serviço</a>
        </div>
        <p><a href="logout.php">Sair</a></p>
    </div>
</footer>

</body>
</html>

<?php
// Fecha a conexão com o banco de dados
mysqli_close($conn);
?>
