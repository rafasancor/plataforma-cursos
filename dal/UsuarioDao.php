<?php
namespace App\Dal;

use App\Dal\Conn;
use App\Model\Usuario;
use \PDO;
use \PDOException;
use \Exception;

abstract class UsuarioDao
{
    public static function cadastrar(Usuario $usuario): int
    {
        try {
            $pdo = Conn::getConn();
            $stmt = $pdo->prepare("INSERT INTO usuarios (email, senha, role) VALUES (?, ?, ?)");
            $stmt->execute(array(
                $usuario->getEmail(),
                password_hash($usuario->getSenha(), PASSWORD_DEFAULT),
                $usuario->getRole()
            ));
            return (int) $pdo->lastInsertId();
        } catch (\PDOException $e) {
            if ($e->getCode() === '23000') {
                throw new \PDOException("Erro: E-mail já cadastrado.");
            }
            throw new \PDOException("Erro ao salvar usuário no Banco de Dados: " . $e->getMessage());
        }
    }

    public static function buscarPorId(int $id): ?Usuario
    {
        try {
            $pdo = Conn::getConn();
            $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id=?");
            $stmt->execute([$id]);

            $dados = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$dados)
                return null;
            return Usuario::criarUsuario(
                $dados["id"],
                $dados["email"],
                $dados["senha"],
                $dados["role"]
            );
        } catch (\PDOException $e) {
            throw new \PDOException("Erro ao buscar usuário por ID: " . $e->getMessage());
        }
    }

    public static function buscarPorEmail(string $email): ?Usuario
    {
        try {
            $pdo = Conn::getConn();
            $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email=?");
            $stmt->execute([$email]);

            $dados = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$dados)
                return null;
            return Usuario::criarUsuario(
                $dados["id"],
                $dados["email"],
                $dados["senha"],
                $dados["role"]
            );
        } catch (\PDOException $e) {
            throw new \PDOException("Erro ao buscar usuário por E-mail: " . $e->getMessage());
        }
    }

    public static function atualizarSenha(int $id, string $novaSenha): void
    {
        try {
            $pdo = Conn::getConn();
            $stmt = $pdo->prepare("UPDATE usuarios SET senha=? WHERE id=?");
            $stmt->execute([password_hash($novaSenha, PASSWORD_DEFAULT), $id]);

            if ($stmt->rowCount() === 0) {
                throw new Exception("Nenhuma senha de usuário foi alterada ou o ID não foi encontrado.");
            }
        } catch (\PDOException $e) {
            throw new \PDOException("Erro ao atualizar senha do usuário: " . $e->getMessage());
        }
    }

    public static function listar(): array
    {
        try {
            $pdo = Conn::getConn();
            $stmt = $pdo->prepare("SELECT * FROM usuarios");
            $stmt->execute();
            $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $usuarios = [];
            foreach ($resultados as $dados) {
                $usuarios[] = Usuario::criarUsuario(
                    $dados["id"],
                    $dados["email"],
                    $dados["senha"],
                    $dados["role"]
                );
            }
            return $usuarios;
        } catch (\PDOException $e) {
            throw new \PDOException("Erro ao listar usuários: " . $e->getMessage());
        }
    }

    public static function editar(Usuario $usuario): void
    {
        try {
            $pdo = Conn::getConn();
            $stmt = $pdo->prepare("UPDATE usuarios SET email=?, role=? WHERE id=?");
            $stmt->execute(array(
                $usuario->getEmail(),
                $usuario->getRole(),
                $usuario->getId()
            ));

            if ($stmt->rowCount() === 0) {
                throw new Exception("Nenhum registro de usuário foi alterado ou o ID não foi encontrado.");
            }
        } catch (\PDOException $e) {
            if ($e->getCode() === '23000') {
                throw new \PDOException("Erro: E-mail já cadastrado para outro usuário.");
            }
            throw new \PDOException("Erro ao alterar usuário: " . $e->getMessage());
        }
    }

    public static function excluir(int $id): void
    {
        try {
            $pdo = Conn::getConn();
            $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id=?");
            $stmt->execute([$id]);

            if ($stmt->rowCount() === 0) {
                throw new Exception("Nenhum registro de usuário foi excluído ou o ID não foi encontrado.");
            }
        } catch (\PDOException $e) {
            throw new \PDOException("Erro ao excluir", 2222);

        }
    }
}