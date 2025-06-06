<?php
namespace App\View\Admin;

use App\Model\Aluno;

class AlunoView
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

    public static function formulario(?string $msg = null, ?Aluno $aluno = null, string $csrfToken): void
    {
        self::displayMessage($msg);
        ?>
        <section>
            <h1><?= isset($aluno) ? "Editar Aluno" : "Cadastrar Novo Aluno" ?></h1>
            <form action="<?= isset($aluno) ? "?p=admin_alunos_edit" : "?p=admin_alunos_add" ?>" method="post">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
                <?php if ($aluno !== null): ?>
                    <label for="id">Id:</label>
                    <input type="text" name="id" id="id" value="<?= htmlspecialchars($aluno->getId()) ?>" readonly>
                <?php endif; ?>

                <label for="nome">Nome Completo:</label>
                <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($aluno?->getNome() ?? "") ?>" required>

                <label for="cpf">CPF (XXX.XXX.XXX-XX):</label>
                <input type="text" id="cpf" name="cpf" value="<?= htmlspecialchars($aluno?->getCpf() ?? "") ?>"
                    pattern="\d{3}\.\d{3}\.\d{3}-\d{2}" placeholder="000.000.000-00" required>

                <label for="email">E-mail:</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($aluno?->getEmail() ?? "") ?>" required>

                <label for="senha">Senha (deixe em branco para não alterar):</label>
                <input type="password" id="senha" name="senha" minlength="6">

                <label for="data_nascimento">Data de Nascimento (DD/MM/AAA):</label>
                <input type="date" id="data_nascimento" name="data_nascimento"
                    value="<?= htmlspecialchars($aluno?->getDataNascimento() ?? "") ?>" required>

                <button type="submit" name="enviaForm">
                    <?= isset($aluno) ? "Confirmar Edição" : "Salvar Aluno" ?>
                </button>
                <button type="reset">Limpar</button>
            </form>
            <p><a href="?p=admin_alunos_list">Voltar para a lista de alunos</a></p>
        </section>
        <?php
    }

    public static function listar(array $alunos, ?int $deletarId = null, ?string $msg = null): void
    {
        self::displayMessage($msg);
        if ($deletarId != null) {
            ?>
            <div class="alert">
                Tem certeza que deseja excluir o aluno com ID #<?= htmlspecialchars($deletarId) ?>?
                <a href="?p=admin_alunos_delete&confirm_del=<?= $deletarId ?>">Confirmar</a> |
                <a href="?p=admin_alunos_list">Cancelar</a>
                <span class="close" onclick="this.parentElement.style.display='none'">&times;</span>
            </div>
            <?php
        }
        ?>
        <section>
            <h1>Gerenciar Alunos</h1>
            <p><a href="?p=admin_alunos_add">Adicionar Novo Aluno</a></p>
            <table>
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Nome</th>
                        <th>CPF</th>
                        <th>E-mail</th>
                        <th>Data de Nascimento</th>
                        <th>Alterar</th>
                        <th>Deletar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($alunos)): ?>
                        <tr>
                            <td colspan="7">Nenhum aluno cadastrado.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($alunos as $aluno): ?>
                            <tr>
                                <td><?= htmlspecialchars($aluno->getId()) ?></td>
                                <td><?= htmlspecialchars($aluno->getNome()) ?></td>
                                <td><?= htmlspecialchars($aluno->getCpf()) ?></td>
                                <td><?= htmlspecialchars($aluno->getEmail()) ?></td>
                                <td><?= htmlspecialchars((new \DateTime($aluno->getDataNascimento()))->format('d/m/Y')) ?></td>
                                <td><a href="?p=admin_alunos_edit&alt=<?= $aluno->getId() ?>">Alterar</a></td>
                                <td><a href="?p=admin_alunos_delete&del=<?= $aluno->getId() ?>">Deletar</a></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
        <?php
    }
}