<?php
namespace App\Model;

class Curso {
    private ?int $id;
    private string $nome;
    private ?string $descricao;
    private ?int $duracao;
    private ?float $preco;
    private string $status;

    private function __construct(
        ?int $id,
        string $nome,
        ?string $descricao,
        ?int $duracao,
        ?float $preco,
        string $status
    ) {
        $this->id = $id;
        $this->nome = $nome;
        $this->descricao = $descricao;
        $this->duracao = $duracao;
        $this->preco = $preco;
        $this->status = $status;
    }

    public static function criarCurso(
        ?int $id,
        string $nome,
        ?string $descricao = null,
        ?int $duracao = null,
        ?float $preco = null,
        string $status = 'ativo'
    ): static {
        if (empty($nome)) {
            throw new \Exception("Nome do curso é obrigatório.");
        }
        if (!in_array($status, ['ativo', 'inativo'])) {
            throw new \Exception("Status do curso inválido.");
        }
        return new static($id, $nome, $descricao, $duracao, $preco, $status);
    }

    public function getId(): ?int { return $this->id; }
    public function getNome(): string { return $this->nome; }
    public function getDescricao(): ?string { return $this->descricao; }
    public function getDuracao(): ?int { return $this->duracao; }
    public function getPreco(): ?float { return $this->preco; }
    public function getStatus(): string { return $this->status; }

    public function setNome(string $nome): void { $this->nome = $nome; }
    public function setDescricao(?string $descricao): void { $this->descricao = $descricao; }
    public function setDuracao(?int $duracao): void { $this->duracao = $duracao; }
    public function setPreco(?float $preco): void { $this->preco = $preco; }
    public function setStatus(string $status): void {
        if (!in_array($status, ['ativo', 'inativo'])) {
            throw new \Exception("Status do curso inválido.");
        }
        $this->status = $status;
    }
}