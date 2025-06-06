<?php
namespace App\Model;

class Usuario {
    private ?int $id;
    private string $email;
    private string $senha;
    private string $role;

    private function __construct(
        ?int $id,
        string $email,
        string $senha,
        string $role
    ) {
        $this->id = $id;
        $this->email = $email;
        $this->senha = $senha;
        $this->role = $role;
    }

    public static function criarUsuario(
        ?int $id,
        string $email,
        string $senha,
        string $role = 'user'
    ): static {
        if (empty($email) || empty($senha)) {
            throw new \Exception("Email e senha são obrigatórios.");
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \Exception("Formato de e-mail inválido.");
        }
        if (!in_array($role, ['admin', 'user'])) {
            throw new \Exception("Papel do usuário inválido.");
        }
        return new static($id, $email, $senha, $role);
    }

    public function getId(): ?int { return $this->id; }
    public function getEmail(): string { return $this->email; }
    public function getSenha(): string { return $this->senha; }
    public function getRole(): string { return $this->role; }

    public function setEmail(string $email): void {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \Exception("Formato de e-mail inválido.");
        }
        $this->email = $email;
    }
    public function setSenha(string $senha): void { $this->senha = $senha; }
    public function setRole(string $role): void {
        if (!in_array($role, ['admin', 'user'])) {
            throw new \Exception("Papel do usuário inválido.");
        }
        $this->role = $role;
    }
}