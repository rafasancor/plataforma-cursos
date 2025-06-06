<?php
namespace App\View\Admin;
?>
<section>
    <h1>Painel do Administrador</h1>
    <p>Bem-vindo ao painel de gerenciamento da plataforma.</p>

    <h2>Gerenciamento de Dados</h2>
    <ul>
        <li><a href="?p=admin_cursos_list">Gerenciar Cursos</a></li>
        <li><a href="?p=admin_alunos_list">Gerenciar Alunos</a></li>
        <li><a href="?p=admin_matriculas_list">Gerenciar Matrículas</a></li>
        <?php if (\App\Controller\AuthController::isAdmin()): ?>
            <li><a href="?p=admin_usuarios_list">Gerenciar Usuários Administradores</a></li>
        <?php endif; ?>
    </ul>
</section>