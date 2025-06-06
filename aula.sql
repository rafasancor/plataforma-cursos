CREATE DATABASE IF NOT EXISTS aula;
USE aula;

-- Tabela de 'cursos'
CREATE TABLE IF NOT EXISTS cursos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    descricao TEXT,
    duracao INT,
    preco DECIMAL(10, 2),
    status ENUM('ativo', 'inativo') DEFAULT 'ativo'
);

-- Tabela de 'alunos'
CREATE TABLE IF NOT EXISTS alunos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    cpf VARCHAR(14) UNIQUE NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    data_nascimento DATE NOT NULL
);

-- Tabela de 'matriculas'
CREATE TABLE IF NOT EXISTS matriculas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_aluno INT NOT NULL,
    id_curso INT NOT NULL,
    data_matricula DATETIME DEFAULT CURRENT_TIMESTAMP,
    status ENUM('ativa', 'concluida', 'cancelada') DEFAULT 'ativa',
    FOREIGN KEY (id_aluno) REFERENCES alunos(id) ON DELETE CASCADE,
    FOREIGN KEY (id_curso) REFERENCES cursos(id) ON DELETE CASCADE
);

-- Tabela de 'usuarios'
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'user'
);

INSERT INTO usuarios (email, senha, role)
VALUES ('admin@gmail.com', '$2y$10$70Lt/yt6M/NCvbocqqDCh.NaHkNx4KMa33QoJ.hN45CzZKaeQh4ze', 'admin');

-- select * from cursos;
-- select * from alunos;
-- select * from matriculas;
-- select * from usuarios;

