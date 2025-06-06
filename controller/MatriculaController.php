<?php
namespace App\Controller;

use App\Util\Functions as Util;
use App\Model\Matricula;
use App\Dal\MatriculaDao;
use App\Dal\AlunoDao;
use App\Dal\CursoDao;
use App\View\Admin\MatriculaView;
use App\Controller\AuthController;

abstract class MatriculaController
{
    public static ?string $msg = null;

    private static function generateCsrfToken(): string
    {
        if (empty($_SESSION['csrf_token_matricula'])) {
            $_SESSION['csrf_token_matricula'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token_matricula'];
    }

    private static function validateCsrfToken(string $token): bool
    {
        if (!isset($_SESSION['csrf_token_matricula']) || $token !== $_SESSION['csrf_token_matricula']) {
            return false;
        }
        unset($_SESSION['csrf_token_matricula']);
        return true;
    }

    public static function cadastrar(): void
    {
        AuthController::requireAdmin();

        $alunos = AlunoDao::listar();
        $cursos = CursoDao::listar();

        if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST["id_aluno"])) {
            [$idAluno, $idCurso, $dataMatricula, $status] = array_map(
                [Util::class, "preparaTexto"],
                [
                    $_POST["id_aluno"],
                    $_POST["id_curso"],
                    $_POST["data_matricula"],
                    $_POST["status"]
                ]
            );
            $csrf_token = $_POST["csrf_token"] ?? '';

            if (!self::validateCsrfToken($csrf_token)) {
                self::$msg = "Erro de segurança: Token CSRF inválido.";
                MatriculaView::formulario(self::$msg, null, $alunos, $cursos, self::generateCsrfToken());
                return;
            }

            try {
                $matricula = Matricula::criarMatricula(
                    null,
                    (int) $idAluno,
                    (int) $idCurso,
                    $dataMatricula,
                    $status
                );
                $id = MatriculaDao::cadastrar($matricula);
                self::$msg = "Matrícula cadastrada com sucesso!";
            } catch (\Exception $e) {
                self::$msg = $e->getMessage();
            }
        }
        MatriculaView::formulario(self::$msg, null, $alunos, $cursos, self::generateCsrfToken());
    }

    public static function editar(): void
    {
        AuthController::requireAdmin();

        $matricula = null;
        if (isset($_GET["alt"])) {
            $matricula = MatriculaDao::buscarPorId((int) $_GET["alt"]);
            if (!$matricula) {
                self::$msg = "Matrícula não encontrada para edição.";
            }
        }

        $alunos = AlunoDao::listar();
        $cursos = CursoDao::listar();

        if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["id"])) {
            [$id, $idAluno, $idCurso, $dataMatricula, $status] = array_map(
                [Util::class, "preparaTexto"],
                [
                    $_POST["id"],
                    $_POST["id_aluno"],
                    $_POST["id_curso"],
                    $_POST["data_matricula"],
                    $_POST["status"]
                ]
            );
            $csrf_token = $_POST["csrf_token"] ?? '';

            if (!self::validateCsrfToken($csrf_token)) {
                self::$msg = "Erro de segurança: Token CSRF inválido.";
                MatriculaView::formulario(self::$msg, $matricula, $alunos, $cursos, self::generateCsrfToken());
                return;
            }

            try {
                $matricula = Matricula::criarMatricula(
                    (int) $id,
                    (int) $idAluno,
                    (int) $idCurso,
                    $dataMatricula,
                    $status
                );
                MatriculaDao::editar($matricula);
                self::$msg = "Matrícula alterada com sucesso!";
            } catch (\Exception $e) {
                self::$msg = $e->getMessage();
            }
        }
        MatriculaView::formulario(self::$msg, $matricula, $alunos, $cursos, self::generateCsrfToken());
    }

    public static function deletar(): void
    {
        AuthController::requireAdmin();

        if (isset($_GET["del"])) {
            self::listar((int) $_GET["del"]);
            return;
        }
        if (isset($_GET["confirm_del"])) {
            try {
                MatriculaDao::excluir((int) $_GET["confirm_del"]);
                self::$msg = "Matrícula excluída com sucesso!";
            } catch (\Exception $e) {
                self::$msg = $e->getMessage();
            }
            header("Location: ?p=admin_matriculas_list");
            exit;
        }
        header("Location: ?p=admin_matriculas_list");
        exit;
    }

    public static function listar(?int $deletarId = null): void
    {
        AuthController::requireAdmin();
        $matriculas = MatriculaDao::listar();
        MatriculaView::listar($matriculas, $deletarId, self::$msg);
    }
}