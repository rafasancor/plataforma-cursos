<?php
namespace App\View\Admin;

use App\Model\Curso;

class CursoView
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

    public static function formulario(?string $msg = null, ?Curso $curso = null, string $csrfToken): void
    {
        self::displayMessage($msg);
        ?>
        <section>
            <h1><?= isset($curso) ? "Editar Curso" : "Cadastrar Novo Curso" ?></h1>
            <form action="<?= isset($curso) ? "?p=admin_cursos_edit" : "?p=admin_cursos_add" ?>" method="post">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
                <?php if ($curso !== null): ?>
                    <label for="id">Id:</label>
                    <input type="text" name="id" id="id" value="<?= htmlspecialchars($curso->getId()) ?>" readonly>
                <?php endif; ?>

                <label for="nome">Nome:</label>
                <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($curso?->getNome() ?? "") ?>" required>

                <label for="descricao">Descrição:</label>
                <textarea id="descricao" name="descricao"><?= htmlspecialchars($curso?->getDescricao() ?? "") ?></textarea>

                <label for="duracao">Duração (horas):</label>
                <input type="number" id="duracao" name="duracao" value="<?= htmlspecialchars($curso?->getDuracao() ?? "") ?>">

                <label for="preco">Preço:</label>
                <input type="text" id="preco" name="preco" value="<?= htmlspecialchars($curso?->getPreco() ?? "") ?>"
                    placeholder="Ex: 99.90">

                <label for="status">Status:</label>
                <select id="status" name="status">
                    <option value="ativo" <?= ($curso?->getStatus() ?? '') === 'ativo' ? 'selected' : '' ?>>Ativo</option>
                    <option value="inativo" <?= ($curso?->getStatus() ?? '') === 'inativo' ? 'selected' : '' ?>>Inativo</option>
                </select>

                <button type="submit" name="enviaForm">
                    <?= isset($curso) ? "Confirmar Edição" : "Salvar Curso" ?>
                </button>
                <button type="reset">Limpar</button>
            </form>
            <p><a href="?p=admin_cursos_list">Voltar para a lista de cursos</a></p>
        </section>
        <?php
    }

    public static function listar(array $cursos, ?int $deletarId = null, ?string $msg = null): void
    {
        self::displayMessage($msg);
        if ($deletarId != null) {
            ?>
            <div class="alert">
                Tem certeza que deseja excluir o curso com ID #<?= htmlspecialchars($deletarId) ?>?
                <a href="?p=admin_cursos_delete&confirm_del=<?= $deletarId ?>">Confirmar</a> |
                <a href="?p=admin_cursos_list">Cancelar</a>
                <span class="close" onclick="this.parentElement.style.display='none'">&times;</span>
            </div>
            <?php
        }
        ?>
        <section>
            <h1>Gerenciar Cursos</h1>
            <p><a href="?p=admin_cursos_add">Adicionar Novo Curso</a></p>
            <table>
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Nome</th>
                        <th>Descrição</th>
                        <th>Duração (horas)</th>
                        <th>Preço</th>
                        <th>Status</th>
                        <th>Alunos Matriculados</th>
                        <th>Alterar</th>
                        <th>Deletar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($cursos)): ?>
                        <tr>
                            <td colspan="9">Nenhum curso cadastrado.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($cursos as $dados):
                            $curso = $dados['curso_obj'];
                            ?>
                            <tr>
                                <td><?= htmlspecialchars($curso->getId()) ?></td>
                                <td><?= htmlspecialchars($curso->getNome()) ?></td>
                                <td><?= htmlspecialchars($curso->getDescricao()) ?></td>
                                <td><?= htmlspecialchars($curso->getDuracao()) ?></td>
                                <td>R$ <?= number_format($curso->getPreco(), 2, ',', '.') ?></td>
                                <td><?= htmlspecialchars($curso->getStatus()) ?></td>
                                <td><?= htmlspecialchars($dados['total_alunos_matriculados'] ?? 0) ?></td>
                                <td><a href="?p=admin_cursos_edit&alt=<?= $curso->getId() ?>">Alterar</a></td>
                                <td><a href="?p=admin_cursos_delete&del=<?= $curso->getId() ?>">Deletar</a></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
        <?php
    }
}