<?php
namespace App\Model;

class Matricula {
    private ?int $id;
    private int $idAluno;
    private int $idCurso;
    private string $dataMatricula;
    private string $status;

    private function __construct(
        ?int $id,
        int $idAluno,
        int $idCurso,
        string $dataMatricula,
        string $status
    ) {
        $this->id = $id;
        $this->idAluno = $idAluno;
        $this->idCurso = $idCurso;
        $this->dataMatricula = $dataMatricula;
        $this->status = $status;
    }

    public static function criarMatricula(
        ?int $id,
        int $idAluno,
        int $idCurso,
        ?string $dataMatricula = null,
        string $status = 'ativa'
    ): static {
        if (empty($idAluno) || empty($idCurso)) {
            throw new \Exception("Aluno e Curso são obrigatórios para a matrícula.");
        }
        if (!in_array($status, ['ativa', 'concluida', 'cancelada'])) {
            throw new \Exception("Status da matrícula inválido.");
        }
        $dataMatricula = $dataMatricula ?? date('Y-m-d H:i:s');
        return new static($id, $idAluno, $idCurso, $dataMatricula, $status);
    }

    public function getId(): ?int { return $this->id; }
    public function getIdAluno(): int { return $this->idAluno; }
    public function getIdCurso(): int { return $this->idCurso; }
    public function getDataMatricula(): string { return $this->dataMatricula; }
    public function getStatus(): string { return $this->status; }

    public function setIdAluno(int $idAluno): void { $this->idAluno = $idAluno; }
    public function setIdCurso(int $idCurso): void { $this->idCurso = $idCurso; }
    public function setDataMatricula(string $dataMatricula): void { $this->dataMatricula = $dataMatricula; }
    public function setStatus(string $status): void {
        if (!in_array($status, ['ativa', 'concluida', 'cancelada'])) {
            throw new \Exception("Status da matrícula inválido.");
        }
        $this->status = $status;
    }
}