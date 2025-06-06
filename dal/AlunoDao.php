<?php
namespace App\Dal;

use App\Dal\Conn;
use App\Model\Aluno;
use \PDO;
use \PDOException;
use \Exception;

abstract class AlunoDao
{
    public static function cadastrar(Aluno $aluno): int
    {
        try {
            $pdo = Conn::getConn();
            $stmt = $pdo->prepare("INSERT INTO alunos (nome, cpf, email, senha, data_nascimento) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute(array(
                $aluno->getNome(),
                $aluno->getCpf(),
                $aluno->getEmail(),
                password_hash($aluno->getSenha(), PASSWORD_DEFAULT),
                $aluno->getDataNascimento()
            ));
            return (int) $pdo->lastInsertId();
        } catch (\PDOException $e) {
            if ($e->getCode() === '23000') {
                throw new \PDOException("Erro: CPF ou E-mail já cadastrado.");
            }
            throw new \PDOException("Erro ao salvar aluno no Banco de Dados: " . $e->getMessage());
        }
    }

    public static function listar(): array
    {
        try {
            $pdo = Conn::getConn();
            $stmt = $pdo->prepare("SELECT * FROM alunos");
            $stmt->execute();
            $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $alunos = [];
            foreach ($resultados as $dados) {
                $alunos[] = Aluno::criarAluno(
                    $dados["id"],
                    $dados["nome"],
                    $dados["cpf"],
                    $dados["email"],
                    $dados["senha"],
                    $dados["data_nascimento"]
                );
            }
            return $alunos;
        } catch (\PDOException $e) {
            throw new \PDOException("Erro ao listar alunos: " . $e->getMessage());
        }
    }

    public static function buscarPorId(int $id): ?Aluno
    {
        try {
            $pdo = Conn::getConn();
            $stmt = $pdo->prepare("SELECT * FROM alunos WHERE id=?");
            $stmt->execute([$id]);

            $dados = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$dados)
                return null;
            return Aluno::criarAluno(
                $dados["id"],
                $dados["nome"],
                $dados["cpf"],
                $dados["email"],
                $dados["senha"],
                $dados["data_nascimento"]
            );
        } catch (\PDOException $e) {
            throw new \PDOException("Erro ao buscar aluno por ID: " . $e->getMessage());
        }
    }

    public static function buscarPorEmail(string $email): ?Aluno
    {
        try {
            $pdo = Conn::getConn();
            $stmt = $pdo->prepare("SELECT * FROM alunos WHERE email=?");
            $stmt->execute([$email]);

            $dados = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$dados)
                return null;
            return Aluno::criarAluno(
                $dados["id"],
                $dados["nome"],
                $dados["cpf"],
                $dados["email"],
                $dados["senha"],
                $dados["data_nascimento"]
            );
        } catch (\PDOException $e) {
            throw new \PDOException("Erro ao buscar aluno por E-mail: " . $e->getMessage());
        }
    }

    public static function buscarPorCpfDataNascimento(string $cpf, string $dataNascimento): ?Aluno
    {
        try {
            $pdo = Conn::getConn();
            $stmt = $pdo->prepare("SELECT * FROM alunos WHERE cpf=? AND data_nascimento=?");
            $stmt->execute([$cpf, $dataNascimento]);

            $dados = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$dados)
                return null;
            return Aluno::criarAluno(
                $dados["id"],
                $dados["nome"],
                $dados["cpf"],
                $dados["email"],
                $dados["senha"],
                $dados["data_nascimento"]
            );
        } catch (\PDOException $e) {
            throw new \PDOException("Erro ao buscar aluno por CPF e Data de Nascimento: " . $e->getMessage());
        }
    }

    public static function editar(Aluno $aluno): void
    {
        try {
            $pdo = Conn::getConn();
            $stmt = $pdo->prepare("UPDATE alunos SET nome=?, cpf=?, email=?, data_nascimento=? WHERE id=?");
            $stmt->execute(array(
                $aluno->getNome(),
                $aluno->getCpf(),
                $aluno->getEmail(),
                $aluno->getDataNascimento(),
                $aluno->getId()
            ));

            if ($stmt->rowCount() === 0) {
                throw new Exception("Nenhum registro de aluno foi alterado ou o ID não foi encontrado.");
            }
        } catch (\PDOException $e) {
            if ($e->getCode() === '23000') {
                throw new \PDOException("Erro: CPF ou E-mail já cadastrado para outro aluno.");
            }
            throw new \PDOException("Erro ao alterar aluno: " . $e->getMessage());
        }
    }

    public static function atualizarSenha(int $id, string $novaSenha): void
    {
        try {
            $pdo = Conn::getConn();
            $stmt = $pdo->prepare("UPDATE alunos SET senha=? WHERE id=?");
            $stmt->execute([password_hash($novaSenha, PASSWORD_DEFAULT), $id]);

            if ($stmt->rowCount() === 0) {
                throw new Exception("Nenhuma senha de aluno foi alterada ou o ID não foi encontrado.");
            }
        } catch (\PDOException $e) {
            throw new \PDOException("Erro ao atualizar senha do aluno: " . $e->getMessage());
        }
    }

    public static function excluir(int $id): void
    {
        try {
            $pdo = Conn::getConn();
            $stmt = $pdo->prepare("DELETE FROM alunos WHERE id=?");
            $stmt->execute([$id]);

            if ($stmt->rowCount() === 0) {
                throw new Exception("Nenhum registro de aluno foi excluído ou o ID não foi encontrado.");
            }
        } catch (\PDOException $e) {
            throw new \PDOException("Erro ao excluir aluno: " . $e->getMessage());
        }
    }
}