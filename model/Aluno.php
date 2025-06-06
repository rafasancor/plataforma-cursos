<?php
namespace App\Model;

class Aluno
{
    private ?int $id;
    private string $nome;
    private string $cpf;
    private string $email;
    private string $senha;
    private string $dataNascimento;

    private function __construct(
        ?int $id,
        string $nome,
        string $cpf,
        string $email,
        string $senha,
        string $dataNascimento
    ) {
        $this->id = $id;
        $this->nome = $nome;
        $this->cpf = $cpf;
        $this->email = $email;
        $this->senha = $senha;
        $this->dataNascimento = $dataNascimento;
    }

    public static function criarAluno(
        ?int $id,
        string $nome,
        string $cpf,
        string $email,
        string $senha,
        string $dataNascimento
    ): static {
        if (empty($nome) || empty($cpf) || empty($email) || empty($senha) || empty($dataNascimento)) {
            throw new \Exception("Todos os campos são obrigatórios para o aluno.");
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \Exception("Formato de e-mail inválido.");
        }
        if (!preg_match("/^\d{3}\.\d{3}\.\d{3}-\d{2}$/", $cpf)) {
            throw new \Exception("Formato de CPF inválido. Use XXX.XXX.XXX-XX.");
        }
        if (!\DateTime::createFromFormat('Y-m-d', $dataNascimento) && !\DateTime::createFromFormat('d/m/Y', $dataNascimento)) {
            throw new \Exception("Formato de data de nascimento inválido. Use DD/MM/AAAA.");
        }

        return new static($id, $nome, $cpf, $email, $senha, $dataNascimento);
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    public function getNome(): string
    {
        return $this->nome;
    }
    public function getCpf(): string
    {
        return $this->cpf;
    }
    public function getEmail(): string
    {
        return $this->email;
    }
    public function getSenha(): string
    {
        return $this->senha;
    }
    public function getDataNascimento(): string
    {
        return $this->dataNascimento;
    }

    public function setNome(string $nome): void
    {
        $this->nome = $nome;
    }
    public function setCpf(string $cpf): void
    {
        $this->cpf = $cpf;
    }
    public function setEmail(string $email): void
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \Exception("Formato de e-mail inválido.");
        }
        $this->email = $email;
    }
    public function setSenha(string $senha): void
    {
        $this->senha = $senha;
    }
    public function setDataNascimento(string $dataNascimento): void
    {
        if (!\DateTime::createFromFormat('Y-m-d', $dataNascimento) && !\DateTime::createFromFormat('d/m/Y', $dataNascimento)) {
            throw new \Exception("Formato de data de nascimento inválido. Use YYYY-MM-DD ou DD/MM/AAAA.");
        }
        $this->dataNascimento = $dataNascimento;
    }
}