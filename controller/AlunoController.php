<?php
namespace App\Controller;

use App\Util\Functions as Util;
use App\Model\Aluno;
use App\Dal\AlunoDao;
use App\View\Admin\AlunoView;
use App\Controller\AuthController;

abstract class AlunoController {
    public static ?string $msg = null;

    private static function generateCsrfToken(): string {
        if (empty($_SESSION['csrf_token_aluno'])) {
            $_SESSION['csrf_token_aluno'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token_aluno'];
    }

    private static function validateCsrfToken(string $token): bool {
        if (!isset($_SESSION['csrf_token_aluno']) || $token !== $_SESSION['csrf_token_aluno']) {
            return false;
        }
        unset($_SESSION['csrf_token_aluno']);
        return true;
    }

    public static function cadastrar(): void {
        AuthController::requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST["nome"])) {
            [$nome, $cpf, $email, $senha, $dataNascimento] = array_map(
                [Util::class, "preparaTexto"],
                [
                    $_POST["nome"],
                    $_POST["cpf"],
                    $_POST["email"],
                    $_POST["senha"],
                    $_POST["data_nascimento"]
                ]
            );
            $csrf_token = $_POST["csrf_token"] ?? '';

            if (!self::validateCsrfToken($csrf_token)) {
                self::$msg = "Erro de segurança: Token CSRF inválido.";
                AlunoView::formulario(self::$msg, null, self::generateCsrfToken());
                return;
            }

            try {
                if (strlen($senha) < 6) {
                    throw new \Exception("A senha deve ter pelo menos 6 caracteres.");
                }
                $aluno = Aluno::criarAluno(null, $nome, $cpf, $email, $senha, $dataNascimento);
                AlunoDao::cadastrar($aluno);
                self::$msg = "Aluno cadastrado com sucesso!";
            } catch (\Exception $e) {
                self::$msg = $e->getMessage();
            }
        }
        AlunoView::formulario(self::$msg, null, self::generateCsrfToken());
    }

    public static function editar(): void {
        AuthController::requireAdmin();

        $aluno = null;
        if (isset($_GET["alt"])) {
            $aluno = AlunoDao::buscarPorId((int) $_GET["alt"]);
            if (!$aluno) {
                self::$msg = "Aluno não encontrado para edição.";
            }
        }

        if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["id"])) {
            [$id, $nome, $cpf, $email, $dataNascimento] = array_map(
                [Util::class, "preparaTexto"],
                [
                    $_POST["id"],
                    $_POST["nome"],
                    $_POST["cpf"],
                    $_POST["email"],
                    $_POST["data_nascimento"]
                ]
            );
            $senha = $_POST["senha"] ?? null;
            $csrf_token = $_POST["csrf_token"] ?? '';

            if (!self::validateCsrfToken($csrf_token)) {
                self::$msg = "Erro de segurança: Token CSRF inválido.";
                AlunoView::formulario(self::$msg, $aluno, self::generateCsrfToken());
                return;
            }

            try {
                $alunoExistente = AlunoDao::buscarPorId((int)$id);
                if (!$alunoExistente) {
                    throw new \Exception("Aluno a ser editado não encontrado.");
                }

                $aluno = Aluno::criarAluno(
                    (int)$id,
                    $nome,
                    $cpf,
                    $email,
                    $alunoExistente->getSenha(),
                    $dataNascimento
                );

                if ($senha && strlen($senha) >= 6) {
                    AlunoDao::atualizarSenha((int)$id, $senha);
                } else if ($senha && strlen($senha) < 6) {
                    throw new \Exception("A nova senha deve ter pelo menos 6 caracteres.");
                }

                AlunoDao::editar($aluno);
                self::$msg = "Aluno alterado com sucesso!";
            } catch (\Exception $e) {
                self::$msg = $e->getMessage();
            }
        }
        AlunoView::formulario(self::$msg, $aluno, self::generateCsrfToken());
    }

    public static function deletar(): void {
        AuthController::requireAdmin();

        if (isset($_GET["del"])) {
            self::listar((int) $_GET["del"]);
            return;
        }
        if (isset($_GET["confirm_del"])) {
            try {
                AlunoDao::excluir((int) $_GET["confirm_del"]);
                self::$msg = "Aluno excluído com sucesso!";
            } catch (\Exception $e) {
                self::$msg = $e->getMessage();
            }
            header("Location: ?p=admin_alunos_list");
            exit;
        }
        header("Location: ?p=admin_alunos_list");
        exit;
    }

    public static function listar(?int $deletarId = null): void {
        AuthController::requireAdmin();
        $alunos = AlunoDao::listar();
        AlunoView::listar($alunos, $deletarId, self::$msg);
    }
}