<?php
namespace App\Controller;

use App\Util\Functions as Util;
use App\Model\Aluno;
use App\Model\Usuario;
use App\Dal\AlunoDao;
use App\Dal\UsuarioDao;
use App\View\AuthView;

abstract class AuthController
{
    public static ?string $msg = null;

    private static function generateCsrfToken(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    private static function validateCsrfToken(string $token): bool
    {
        if (!isset($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) {
            return false;
        }

        unset($_SESSION['csrf_token']);
        return true;
    }

    public static function login(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST["email"])) {
            $email = Util::preparaTexto($_POST["email"]);
            $senha = $_POST["senha"];
            $csrf_token = $_POST["csrf_token"] ?? '';

            if (!self::validateCsrfToken($csrf_token)) {
                self::$msg = "Erro de segurança: Token CSRF inválido.";
                AuthView::login(self::$msg, self::generateCsrfToken());
                return;
            }

            try {
                $aluno = AlunoDao::buscarPorEmail($email);
                if ($aluno && password_verify($senha, $aluno->getSenha())) {
                    $_SESSION['user_id'] = $aluno->getId();
                    $_SESSION['user_email'] = $aluno->getEmail();
                    $_SESSION['user_role'] = 'aluno';
                    header("Location: ?p=cursos");
                    exit;
                }


                $usuario = UsuarioDao::buscarPorEmail($email);
                if ($usuario && password_verify($senha, $usuario->getSenha())) {
                    $_SESSION['user_id'] = $usuario->getId();
                    $_SESSION['user_email'] = $usuario->getEmail();
                    $_SESSION['user_role'] = $usuario->getRole();
                    if ($usuario->getRole() === 'admin') {
                        header("Location: ?p=admin_dashboard");
                    } else {
                        header("Location: ?p=cursos");
                    }
                    exit;
                }

                self::$msg = "Email ou senha incorretos.";
            } catch (\Exception $e) {
                self::$msg = "Erro no login: " . $e->getMessage();
            }
        }
        AuthView::login(self::$msg, self::generateCsrfToken());
    }

    public static function logout(): void
    {
        session_unset();
        session_destroy();
        // Clear cookies if "remember me" was implemented
        if (isset($_COOKIE['remember_me'])) {
            setcookie('remember_me', '', time() - 3600, '/');
        }
        header("Location: ?p=home");
        exit;
    }

    public static function register(): void
    {
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
                AuthView::register(self::$msg, self::generateCsrfToken());
                return;
            }

            try {
                if (strlen($senha) < 6) {
                    throw new \Exception("A senha deve ter pelo menos 6 caracteres.");
                }

                $aluno = Aluno::criarAluno(null, $nome, $cpf, $email, $senha, $dataNascimento);
                AlunoDao::cadastrar($aluno);
                self::$msg = "Cadastro realizado com sucesso! Faça login.";

                AuthView::register(self::$msg, self::generateCsrfToken());
            } catch (\Exception $e) {
                self::$msg = $e->getMessage();
                AuthView::register(self::$msg, self::generateCsrfToken());
            }
        } else {
            AuthView::register(self::$msg, self::generateCsrfToken());
        }
    }

    public static function recoverPassword(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST["cpf"])) {
            [$cpf, $dataNascimento] = array_map(
                [Util::class, "preparaTexto"],
                [
                    $_POST["cpf"],
                    $_POST["data_nascimento"]
                ]
            );
            $csrf_token = $_POST["csrf_token"] ?? '';

            if (!self::validateCsrfToken($csrf_token)) {
                self::$msg = "Erro de segurança: Token CSRF inválido.";
                AuthView::recoverPassword(self::$msg, self::generateCsrfToken());
                return;
            }

            try {
                $aluno = AlunoDao::buscarPorCpfDataNascimento($cpf, $dataNascimento);
                if ($aluno) {

                    $newPassword = substr(bin2hex(random_bytes(8)), 0, 8); // Generate a random 8-char password
                    AlunoDao::atualizarSenha($aluno->getId(), $newPassword);
                    self::$msg = "Uma nova senha temporária foi 'enviada' para seu e-mail (" . $aluno->getEmail() . "): " . $newPassword . ". Por favor, faça login e altere-a.";
                } else {
                    self::$msg = "CPF ou Data de Nascimento não correspondem a nenhum aluno cadastrado.";
                }
            } catch (\Exception $e) {
                self::$msg = "Erro na recuperação de senha: " . $e->getMessage();
            }
        }
        AuthView::recoverPassword(self::$msg, self::generateCsrfToken());
    }

    public static function isAdmin(): bool
    {
        return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
    }

    public static function isAuthenticated(): bool
    {
        return isset($_SESSION['user_id']);
    }

    public static function requireAdmin(): void
    {
        if (!self::isAdmin()) {
            header("Location: ?p=login");
            exit;
        }
    }

    public static function requireLogin(): void
    {
        if (!self::isAuthenticated()) {
            header("Location: ?p=login");
            exit;
        }
    }
}