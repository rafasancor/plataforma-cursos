<?php
declare(strict_types=1);

// Rafael dos Santos Correia
// RGM: 8838913402

namespace App;

session_start();

use App\Controller\CursoController;
use App\Controller\AlunoController;
use App\Controller\MatriculaController;
use App\Controller\AuthController;
use App\Dal\CursoDao;

require_once("./autoload.php");
?>
<!DOCTYPE HTML>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Plataforma de Cursos Online</title>
    <link rel="stylesheet" href="./assets/style.css">

</head>

<body>
    <header>
        <nav>
            <?php require_once("./menu.php"); ?>

        </nav>
    </header>
    <main>
        <?php
        $page = $_GET["p"] ?? "home";


        match ($page) {
            "home" => (function () {
                    $featuredCourses = CursoDao::listar();
                    require_once("./view/home.php");
                })(),
            "cursos" => CursoController::listarCursosPublico(),
            "sobre_nos" => require_once("./view/sobreNos.php"),

            // Rotas de  autenticação
            "login" => AuthController::login(),
            "cadastro" => AuthController::register(),
            "recuperar_senha" => AuthController::recoverPassword(),
            "logout" => AuthController::logout(),

            // Rotas de Administrador (requer autenticação de administrador)
            "admin_dashboard" => require_once("./view/admin/dashboard.php"),

            // Curso CRUD para administrador
            "admin_cursos_list" => CursoController::listarAdmin(),
            "admin_cursos_add" => CursoController::cadastrar(),
            "admin_cursos_edit" => CursoController::editar(),
            "admin_cursos_delete" => CursoController::deletar(),

            // Aluno CRUD para administrador
            "admin_alunos_list" => AlunoController::listar(),
            "admin_alunos_add" => AlunoController::cadastrar(),
            "admin_alunos_edit" => AlunoController::editar(),
            "admin_alunos_delete" => AlunoController::deletar(),

            // Matricula CRUD para administrador
            "admin_matriculas_list" => MatriculaController::listar(),
            "admin_matriculas_add" => MatriculaController::cadastrar(),
            "admin_matriculas_edit" => MatriculaController::editar(),
            "admin_matriculas_delete" => MatriculaController::deletar(),

            // Gerenciamento de usuários para administradores
            "admin_usuarios_list" => \App\Controller\UsuarioController::listar(),
            "admin_usuarios_add" => \App\Controller\UsuarioController::cadastrar(),
            "admin_usuarios_edit" => \App\Controller\UsuarioController::editar(),
            "admin_usuarios_delete" => \App\Controller\UsuarioController::deletar(),

            default => require_once("./view/404.php"), //
        };
        ?>
    </main>
    <footer>
        <?php require_once("./footer.php"); ?>
    </footer>
</body>

</html>