<?php
namespace App\Dal;

use App\Dal\Conn;
use App\Model\Matricula;
use \PDO;
use \PDOException;
use \Exception;

abstract class MatriculaDao
{
    public static function cadastrar(Matricula $matricula): int
    {
        try {
            $pdo = Conn::getConn();
            $stmt = $pdo->prepare("INSERT INTO matriculas (id_aluno, id_curso, data_matricula, status) VALUES (?, ?, ?, ?)");
            $stmt->execute(array(
                $matricula->getIdAluno(),
                $matricula->getIdCurso(),
                $matricula->getDataMatricula(),
                $matricula->getStatus()
            ));
            return (int) $pdo->lastInsertId();
        } catch (\PDOException $e) {
            throw new \PDOException("Erro ao salvar matrícula no Banco de Dados: " . $e->getMessage());
        }
    }

    public static function listar(): array
    {
        try {
            $pdo = Conn::getConn();
            $stmt = $pdo->prepare("
                SELECT m.*, a.nome AS aluno_nome, c.nome AS curso_nome
                FROM matriculas m
                JOIN alunos a ON m.id_aluno = a.id
                JOIN cursos c ON m.id_curso = c.id
            ");
            $stmt->execute();
            $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $matriculas = [];
            foreach ($resultados as $dados) {
                $matricula = Matricula::criarMatricula(
                    $dados["id"],
                    $dados["id_aluno"],
                    $dados["id_curso"],
                    $dados["data_matricula"],
                    $dados["status"]
                );
                $matriculas[] = array_merge($dados, ['matricula_obj' => $matricula]);
            }
            return $matriculas;
        } catch (\PDOException $e) {
            throw new \PDOException("Erro ao listar matrículas: " . $e->getMessage());
        }
    }

    public static function buscarPorId(int $id): ?Matricula
    {
        try {
            $pdo = Conn::getConn();
            $stmt = $pdo->prepare("SELECT * FROM matriculas WHERE id=?");
            $stmt->execute([$id]);

            $dados = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$dados)
                return null;
            return Matricula::criarMatricula(
                $dados["id"],
                $dados["id_aluno"],
                $dados["id_curso"],
                $dados["data_matricula"],
                $dados["status"]
            );
        } catch (\PDOException $e) {
            throw new \PDOException("Erro ao buscar matrícula por ID: " . $e->getMessage());
        }
    }

    public static function editar(Matricula $matricula): void
    {
        try {
            $pdo = Conn::getConn();
            $stmt = $pdo->prepare("UPDATE matriculas SET id_aluno=?, id_curso=?, data_matricula=?, status=? WHERE id=?");
            $stmt->execute(array(
                $matricula->getIdAluno(),
                $matricula->getIdCurso(),
                $matricula->getDataMatricula(),
                $matricula->getStatus(),
                $matricula->getId()
            ));

            if ($stmt->rowCount() === 0) {
                throw new Exception("Nenhum registro de matrícula foi alterado ou o ID não foi encontrado.");
            }
        } catch (\PDOException $e) {
            throw new \PDOException("Erro ao alterar matrícula: " . $e->getMessage());
        }
    }

    public static function excluir(int $id): void
    {
        try {
            $pdo = Conn::getConn();
            $stmt = $pdo->prepare("DELETE FROM matriculas WHERE id=?");
            $stmt->execute([$id]);

            if ($stmt->rowCount() === 0) {
                throw new Exception("Nenhum registro de matrícula foi excluído ou o ID não foi encontrado.");
            }
        } catch (\PDOException $e) {
            throw new \PDOException("Erro ao excluir matrícula: " . $e->getMessage());
        }
    }
}