-- Create database if not exists
CREATE DATABASE IF NOT EXISTS aula; --
USE aula; --

-- Table for 'cursos'
CREATE TABLE IF NOT EXISTS cursos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    descricao TEXT,
    duracao INT, -- in hours, for example
    preco DECIMAL(10, 2),
    status ENUM('ativo', 'inativo') DEFAULT 'ativo'
);

-- Table for 'alunos'
CREATE TABLE IF NOT EXISTS alunos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    cpf VARCHAR(14) UNIQUE NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    data_nascimento DATE NOT NULL
);

-- Table for 'matriculas'
CREATE TABLE IF NOT EXISTS matriculas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_aluno INT NOT NULL,
    id_curso INT NOT NULL,
    data_matricula DATETIME DEFAULT CURRENT_TIMESTAMP,
    status ENUM('ativa', 'concluida', 'cancelada') DEFAULT 'ativa',
    FOREIGN KEY (id_aluno) REFERENCES alunos(id) ON DELETE CASCADE,
    FOREIGN KEY (id_curso) REFERENCES cursos(id) ON DELETE CASCADE
);

-- Table for 'usuarios' (admin users) - this is a generic user table, if different from 'alunos'
-- If 'alunos' are the only users, then this table might not be strictly necessary,
-- but for admin roles, it's good to have a separate user table.
-- I'll assume 'alunos' are the public users, and 'usuarios' are administrators.
CREATE TABLE IF NOT EXISTS usuarios ( --
    id INT AUTO_INCREMENT PRIMARY KEY, --
    email VARCHAR(255) UNIQUE NOT NULL, --
    senha VARCHAR(255) NOT NULL, --
    role ENUM('admin', 'user') DEFAULT 'user' -- To differentiate between admins and regular users
);

INSERT INTO usuarios (email, senha, role) 
VALUES ('admin@gmail.com', '$2y$10$X6f8G0nE5KwDZ9BzIX9c6l7y88/dByZv4VVRauZlAcvZ1NeTwKzeq', 'admin');

select * from alunos;


INSERT INTO usuarios (email, senha, role) VALUES ('admin@gmail.com', '$2y$10$70Lt/yt6M/NCvbocqqDCh.NaHkNx4KMa33QoJ.hN45CzZKaeQh4ze', 'admin');

