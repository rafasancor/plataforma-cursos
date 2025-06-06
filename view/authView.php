<?php
namespace App\View;

class AuthView
{
    public static function displayMessage(?string $msg = null): void
    {
        if ($msg !== null): ?>
            <div class="alert">
                <?= htmlspecialchars($msg) ?>
                <span class="close" onclick="this.parentElement.style.display='none'">&times;</span>
            </div>
        <?php endif;
    }

    public static function login(?string $msg = null, string $csrfToken): void
    {
        ?>
        <section>
            <h1>Login</h1>
            <?php self::displayMessage($msg); ?>
            <form action="?p=login" method="post">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
                <label for="email">E-mail:</label>
                <input type="email" id="email" name="email" required>

                <label for="senha">Senha:</label>
                <input type="password" id="senha" name="senha" required>

                <button type="submit">Entrar</button>
            </form>
            <p>Ainda não tem conta? <a href="?p=cadastro">Cadastre-se aqui</a>.</p>
            <p><a href="?p=recuperar_senha">Esqueceu sua senha?</a></p>
        </section>
        <?php
    }

    public static function register(?string $msg = null, string $csrfToken): void
    {
        ?>
        <section>
            <h1>Cadastro de Aluno</h1>
            <?php self::displayMessage($msg); ?>
            <form action="?p=cadastro" method="post">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
                <label for="nome">Nome Completo:</label>
                <input type="text" id="nome" name="nome" required>

                <label for="cpf">CPF (XXX.XXX.XXX-XX):</label>
                <input type="text" id="cpf" name="cpf" pattern="\d{3}\.\d{3}\.\d{3}-\d{2}" placeholder="000.000.000-00"
                    required>

                <label for="email">E-mail:</label>
                <input type="email" id="email" name="email" required>

                <label for="senha">Senha:</label>
                <input type="password" id="senha" name="senha" required minlength="6">

                <label for="data_nascimento">Data de Nascimento (DD/MM/AAAA):</label>
                <input type="date" id="data_nascimento" name="data_nascimento" required>

                <button type="submit">Cadastrar</button>
            </form>
            <p>Já tem conta? <a href="?p=login">Faça login aqui</a>.</p>
        </section>
        <?php
    }

    public static function recoverPassword(?string $msg = null, string $csrfToken): void
    {
        ?>
        <section>
            <h1>Recuperar Senha</h1>
            <?php self::displayMessage($msg); ?>
            <form action="?p=recuperar_senha" method="post">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
                <p>Informe seu CPF e Data de Nascimento para recuperar sua senha.</p>
                <label for="cpf">CPF (XXX.XXX.XXX-XX):</label>
                <input type="text" id="cpf" name="cpf" pattern="\d{3}\.\d{3}\.\d{3}-\d{2}" placeholder="000.000.000-00"
                    required>

                <label for="data_nascimento">Data de Nascimento (DD/MM/AAAA):</label>
                <input type="date" id="data_nascimento" name="data_nascimento" required>

                <button type="submit">Recuperar Senha</button>
            </form>
            <p><a href="?p=login">Voltar para o Login</a></p>
        </section>
        <?php
    }
}