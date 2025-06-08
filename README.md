# plataforma-cursos

Este projeto é uma plataforma de cursos online desenvolvida em PHP, criada para a disciplina de Desenvolvimento de Sistemas.

## Visão Geral

A Plataforma de Cursos Online oferece uma variedade de cursos para impulsionar a carreira e o conhecimento dos usuários. Ela permite que os alunos estudem no seu próprio ritmo, de qualquer lugar, com conteúdo adaptável ao seu estilo de vida e nível de conhecimento.

## Funcionalidades

### Funcionalidades do Usuário (Aluno)

* **Autenticação**:
    * Login de usuário/aluno.
    * Cadastro de novos alunos.
    * Recuperação de senha para alunos.
    * Logout.
* **Cursos**:
    * Visualização de todos os cursos disponíveis.
    * Visualização de cursos em destaque na página inicial.

### Funcionalidades do Administrador

* **Painel Administrativo**: Acesso a um painel de gerenciamento.
* **Gerenciamento de Cursos (CRUD)**:
    * Adicionar novos cursos.
    * Editar cursos existentes.
    * Listar todos os cursos, incluindo o número de alunos matriculados.
    * Deletar cursos.
* **Gerenciamento de Alunos (CRUD)**:
    * Adicionar novos alunos.
    * Editar informações de alunos existentes.
    * Listar todos os alunos.
    * Deletar alunos.
* **Gerenciamento de Matrículas (CRUD)**:
    * Adicionar novas matrículas de alunos em cursos.
    * Editar matrículas existentes.
    * Listar todas as matrículas, incluindo os nomes do aluno e do curso.
    * Deletar matrículas.
* **Gerenciamento de Usuários Administradores (CRUD)**:
    * Adicionar novos usuários com privilégios de administrador.
    * Editar usuários administradores existentes.
    * Listar todos os usuários administradores.
    * Deletar usuários administradores.

## Estrutura do Projeto

O projeto segue uma arquitetura MVC (Model-View-Controller) com uma camada de acesso a dados (DAL).

* `assets/`: Contém arquivos de estilo CSS.
* `controller/`: Lógica de controle para diferentes entidades (Aluno, Auth, Curso, Matrícula, Usuário).
* `dal/`: Camada de Acesso a Dados (Data Access Layer) para interagir com o banco de dados (AlunoDao, Conn, CursoDao, MatriculaDao, UsuarioDao).
* `model/`: Definição dos modelos de dados (Aluno, Curso, Matricula, Usuario).
* `util/`: Funções utilitárias.
* `view/`: Arquivos de visualização (páginas HTML/PHP).
* `autoload.php`: Responsável pelo carregamento automático das classes.
* `aula.sql`: Script SQL para criação do banco de dados e tabelas.
* `index.php`: Ponto de entrada da aplicação, roteamento das requisições.
* `menu.php`: Componente de menu de navegação.
* `footer.php`: Componente de rodapé.
* `README.md`: Este arquivo.

## Instalação e Configuração

### Requisitos

* Servidor Web (Apache, Nginx, etc.)
* PHP (versão 8.0 ou superior recomendada)
* MySQL/MariaDB

### Passos para Instalação

1.  **Clone o Repositório**:
    ```bash
    git clone <URL_DO_SEU_REPOSITORIO>
    cd plataforma-cursos
    ```

2.  **Configurar o Banco de Dados**:
    * Acesse seu sistema de gerenciamento de banco de dados (ex: phpMyAdmin).
    * Crie um banco de dados chamado `aula`.
    * Importe o arquivo `aula.sql` para criar as tabelas necessárias e o usuário administrador inicial.
    * O usuário administrador padrão é `admin@gmail.com` com a senha `admin01`.

3.  **Configurar a Conexão com o Banco de Dados**:
    * O arquivo `dal/Conn.php` contém as configurações de conexão. Por padrão, está configurado para `localhost`, usuário `root` e senha vazia.
    * Se suas credenciais forem diferentes, edite o arquivo `dal/Conn.php`:
        ```php
        private static string $host = "localhost"; // Seu host
        private static string $dbName = "aula"; // Nome do seu banco de dados
        private static string $usuario = "root"; // Seu usuário do banco de dados
        private static string $senha = ""; // Sua senha do banco de dados
        ```

4.  **Acessar a Aplicação**:
    * Coloque a pasta do projeto no diretório raiz do seu servidor web.
    * Acesse a aplicação pelo seu navegador: `http://localhost/plataforma-cursos/` (ou o caminho configurado no seu servidor).

## Uso

* **Páginas Públicas**: Acesse a home, cursos e sobre nós sem necessidade de login.
* **Login**: Utilize `admin@gmail.com` e a senha `admin01` para acessar o painel administrativo ou cadastre um novo aluno.
* **Painel Administrativo**: Após o login como administrador, você terá acesso ao painel para gerenciar cursos, alunos, matrículas e outros usuários.


## Licença

Este projeto é de código aberto e está disponível sob a licença MIT.

## Autor

**@rafasancor**
