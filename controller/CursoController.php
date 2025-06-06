<?php
namespace App\Controller;

use App\Util\Functions as Util;
use App\Model\Curso;
use App\Dal\CursoDao;
use App\View\Admin\CursoView;
use App\Controller\AuthController;

abstract class CursoController
{
    public static ?string $msg = null;

    private static function generateCsrfToken(): string
    {
        if (empty($_SESSION['csrf_token_curso'])) {
            $_SESSION['csrf_token_curso'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token_curso'];
    }

    private static function validateCsrfToken(string $token): bool
    {
        if (!isset($_SESSION['csrf_token_curso']) || $token !== $_SESSION['csrf_token_curso']) {
            return false;
        }
        unset($_SESSION['csrf_token_curso']);
        return true;
    }

    public static function cadastrar(): void
    {
        AuthController::requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST["nome"])) {
            [$nome, $descricao, $duracao, $preco, $status] = array_map(
                [Util::class, "preparaTexto"],
                [
                    $_POST["nome"],
                    $_POST["descricao"],
                    $_POST["duracao"],
                    $_POST["preco"],
                    $_POST["status"]
                ]
            );
            $csrf_token = $_POST["csrf_token"] ?? '';

            if (!self::validateCsrfToken($csrf_token)) {
                self::$msg = "Erro de segurança: Token CSRF inválido.";
                CursoView::formulario(self::$msg, null, self::generateCsrfToken());
                return;
            }

            try {
                $curso = Curso::criarCurso(
                    null,
                    $nome,
                    $descricao,
                    (int) $duracao,
                    (float) str_replace(',', '.', $preco),
                    $status
                );
                $id = CursoDao::cadastrar($curso);
                self::$msg = "Curso cadastrado com sucesso!";

            } catch (\Exception $e) {
                self::$msg = $e->getMessage();
            }
        }
        CursoView::formulario(self::$msg, null, self::generateCsrfToken());
    }

    public static function editar(): void
    {
        AuthController::requireAdmin();

        $curso = null;
        if (isset($_GET["alt"])) {
            $curso = CursoDao::buscarPorId((int) $_GET["alt"]);
            if (!$curso) {
                self::$msg = "Curso não encontrado para edição.";
            }
        }

        if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["id"])) {
            [$id, $nome, $descricao, $duracao, $preco, $status] = array_map(
                [Util::class, "preparaTexto"],
                [
                    $_POST["id"],
                    $_POST["nome"],
                    $_POST["descricao"],
                    $_POST["duracao"],
                    $_POST["preco"],
                    $_POST["status"]
                ]
            );
            $csrf_token = $_POST["csrf_token"] ?? '';

            if (!self::validateCsrfToken($csrf_token)) {
                self::$msg = "Erro de segurança: Token CSRF inválido.";
                CursoView::formulario(self::$msg, $curso, self::generateCsrfToken());
                return;
            }

            try {
                $curso = Curso::criarCurso(
                    (int) $id,
                    $nome,
                    $descricao,
                    (int) $duracao,
                    (float) str_replace(',', '.', $preco),
                    $status
                );
                CursoDao::editar($curso);
                self::$msg = "Curso alterado com sucesso!";

            } catch (\Exception $e) {
                self::$msg = $e->getMessage();
            }
        }
        CursoView::formulario(self::$msg, $curso, self::generateCsrfToken());
    }

    public static function deletar(): void
    {
        AuthController::requireAdmin();

        if (isset($_GET["del"])) {
            self::listarAdmin((int) $_GET["del"]);
            return;
        }
        if (isset($_GET["confirm_del"])) {
            try {
                CursoDao::excluir((int) $_GET["confirm_del"]);
                self::$msg = "Curso excluído com sucesso!";
            } catch (\Exception $e) {
                self::$msg = $e->getMessage();
            }
            header("Location: ?p=admin_cursos_list");
            exit;
        }
        header("Location: ?p=admin_cursos_list");
        exit;
    }

    public static function listarAdmin(?int $deletarId = null): void
    {
        AuthController::requireAdmin();
        $cursos = CursoDao::listar();
        CursoView::listar($cursos, $deletarId, self::$msg);
    }

    public static function listarCursosPublico(): void
    {
        $cursos = CursoDao::listar();

        \App\View\CursosView::listar($cursos);
    }
}