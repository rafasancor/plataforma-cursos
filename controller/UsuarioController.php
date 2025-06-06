<?php
namespace App\Controller;

use App\Util\Functions as Util;
use App\Model\Usuario;
use App\Dal\UsuarioDao;
use App\View\Admin\UsuarioView;
use App\Controller\AuthController;

abstract class UsuarioController
{
    public static ?string $msg = null;

    private static function generateCsrfToken(): string
    {
        if (empty($_SESSION['csrf_token_usuario'])) {
            $_SESSION['csrf_token_usuario'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token_usuario'];
    }

    private static function validateCsrfToken(string $token): bool
    {
        if (!isset($_SESSION['csrf_token_usuario']) || $token !== $_SESSION['csrf_token_usuario']) {
            return false;
        }
        unset($_SESSION['csrf_token_usuario']);
        return true;
    }

    public static function cadastrar(): void
    {
        AuthController::requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST["email"])) {
            [$email, $senha, $role] = array_map(
                [Util::class, "preparaTexto"],
                [
                    $_POST["email"],
                    $_POST["senha"],
                    $_POST["role"]
                ]
            );
            $csrf_token = $_POST["csrf_token"] ?? '';

            if (!self::validateCsrfToken($csrf_token)) {
                self::$msg = "Erro de segurança: Token CSRF inválido.";
                UsuarioView::formulario(self::$msg, null, self::generateCsrfToken());
                return;
            }

            try {
                if (strlen($senha) < 6) {
                    throw new \Exception("A senha deve ter pelo menos 6 caracteres.");
                }
                $usuario = Usuario::criarUsuario(null, $email, $senha, $role);
                UsuarioDao::cadastrar($usuario);
                self::$msg = "Usuário administrador cadastrado com sucesso!";
            } catch (\Exception $e) {
                self::$msg = $e->getMessage();
            }
        }
        UsuarioView::formulario(self::$msg, null, self::generateCsrfToken());
    }

    public static function editar(): void
    {
        AuthController::requireAdmin();

        $usuario = null;
        if (isset($_GET["alt"])) {
            $usuario = UsuarioDao::buscarPorEmail($_GET["alt"]);
            if (is_numeric($_GET["alt"])) {
                $usuario = UsuarioDao::buscarPorId((int) $_GET["alt"]);
            }
            if (!$usuario) {
                self::$msg = "Usuário administrador não encontrado para edição.";
            }
        }

        if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["id"])) {
            [$id, $email, $role] = array_map(
                [Util::class, "preparaTexto"],
                [
                    $_POST["id"],
                    $_POST["email"],
                    $_POST["role"]
                ]
            );
            $senha = $_POST["senha"] ?? null;
            $csrf_token = $_POST["csrf_token"] ?? '';

            if (!self::validateCsrfToken($csrf_token)) {
                self::$msg = "Erro de segurança: Token CSRF inválido.";
                UsuarioView::formulario(self::$msg, $usuario, self::generateCsrfToken());
                return;
            }

            try {
                $usuarioExistente = UsuarioDao::buscarPorId((int) $id);
                if (!$usuarioExistente) {
                    throw new \Exception("Usuário administrador a ser editado não encontrado.");
                }

                $usuario = Usuario::criarUsuario(
                    (int) $id,
                    $email,
                    $usuarioExistente->getSenha(),
                    $role
                );

                if ($senha && strlen($senha) >= 6) {
                    UsuarioDao::atualizarSenha((int) $id, $senha);
                } else if ($senha && strlen($senha) > 0 && strlen($senha) < 6) {
                    throw new \Exception("A nova senha deve ter pelo menos 6 caracteres.");
                }

                UsuarioDao::editar($usuario);
                self::$msg = "Usuário administrador alterado com sucesso!";
            } catch (\Exception $e) {
                self::$msg = $e->getMessage();
            }
        }
        UsuarioView::formulario(self::$msg, $usuario, self::generateCsrfToken());
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
                UsuarioDao::excluir((int) $_GET["confirm_del"]);
                self::$msg = "Usuário administrador excluído com sucesso!";
            } catch (\Exception $e) {
                self::$msg = $e->getMessage();
            }
            header("Location: ?p=admin_usuarios_list");
            exit;
        }
        header("Location: ?p=admin_usuarios_list");
        exit;
    }

    public static function listar(?int $deletarId = null): void
    {
        AuthController::requireAdmin();
        $usuarios = UsuarioDao::listar();
        UsuarioView::listar($usuarios, $deletarId, self::$msg);
    }
}