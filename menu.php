<?php
// session_start(); // Ensure session is started for AuthController checks
// session_destroy()
use App\Controller\AuthController;
?>
<a href="./?p=home">Home</a>
<a href="./?p=cursos">Cursos</a>
<a href="./?p=sobre_nos">Sobre Nós</a>

<?php if (AuthController::isAuthenticated()): ?>
    <?php if (AuthController::isAdmin()): ?>
        <a href="./?p=admin_dashboard">Painel Admin</a>
    <?php endif; ?>
    <span>Olá, <?= htmlspecialchars($_SESSION['user_email'] ?? 'Usuário') ?>!</span>
    <a id="btnSair" href="./?p=logout">Sair</a>
<?php else: ?>
    <a href="./?p=login">Login</a>
    <a href="./?p=cadastro">Cadastro</a>
<?php endif; ?>