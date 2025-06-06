<?php
namespace App\Dal;

use App\Dal\Conn;
use App\Model\Curso;
use \PDO;
use \PDOException;
use \Exception;

abstract class CursoDao
{
    public static function cadastrar(Curso $curso): int
    {
        try {
            $pdo = Conn::getConn();
            $stmt = $pdo->prepare("INSERT INTO cursos (nome, descricao, duracao, preco, status) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute(array(
                $curso->getNome(),
                $curso->getDescricao(),
                $curso->getDuracao(),
                $curso->getPreco(),
                $curso->getStatus()
            ));
            return (int) $pdo->lastInsertId();
        } catch (\PDOException $e) {
            throw new \PDOException("Erro ao salvar curso no Banco de Dados: " . $e->getMessage());
        }
    }

    public static function listar(): array
    {
        try {
            $pdo = Conn::getConn();
            $stmt = $pdo->prepare("
                SELECT c.*, COUNT(m.id_aluno) AS total_alunos_matriculados
                FROM cursos c
                LEFT JOIN matriculas m ON c.id = m.id_curso
                GROUP BY c.id
            ");
            $stmt->execute();
            $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $cursos = [];
            foreach ($resultados as $dados) {
                $curso = Curso::criarCurso(
                    $dados["id"],
                    $dados["nome"],
                    $dados["descricao"],
                    $dados["duracao"],
                    $dados["preco"],
                    $dados["status"]
                );
                $cursos[] = array_merge($dados, ['curso_obj' => $curso]);
            }
            return $cursos;
        } catch (\PDOException $e) {
            throw new \PDOException("Erro ao listar cursos: " . $e->getMessage());
        }
    }

    public static function buscarPorId(int $id): ?Curso
    {
        try {
            $pdo = Conn::getConn();
            $stmt = $pdo->prepare("SELECT * FROM cursos WHERE id=?");
            $stmt->execute([$id]);

            $dados = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$dados)
                return null;
            return Curso::criarCurso(
                $dados["id"],
                $dados["nome"],
                $dados["descricao"],
                $dados["duracao"],
                $dados["preco"],
                $dados["status"]
            );
        } catch (\PDOException $e) {
            throw new \PDOException("Erro ao buscar curso por ID: " . $e->getMessage());
        }
    }

    public static function editar(Curso $curso): void
    {
        try {
            $pdo = Conn::getConn();
            $stmt = $pdo->prepare("UPDATE cursos SET nome=?, descricao=?, duracao=?, preco=?, status=? WHERE id=?");
            $stmt->execute(array(
                $curso->getNome(),
                $curso->getDescricao(),
                $curso->getDuracao(),
                $curso->getPreco(),
                $curso->getStatus(),
                $curso->getId()
            ));

            if ($stmt->rowCount() === 0) {
                throw new Exception("Nenhum registro de curso foi alterado ou o ID não foi encontrado.");
            }
        } catch (\PDOException $e) {
            throw new \PDOException("Erro ao alterar curso: " . $e->getMessage());
        }
    }

    public static function excluir(int $id): void
    {
        try {
            $pdo = Conn::getConn();
            $stmt = $pdo->prepare("DELETE FROM cursos WHERE id=?");
            $stmt->execute([$id]);

            if ($stmt->rowCount() === 0) {
                throw new Exception("Nenhum registro de curso foi excluído ou o ID não foi encontrado.");
            }
        } catch (\PDOException $e) {
            throw new \PDOException("Erro ao excluir curso: " . $e->getMessage());
        }
    }
}