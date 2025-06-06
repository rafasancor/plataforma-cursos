<?php
namespace App\View\Admin;

use App\Model\Matricula;
use App\Model\Aluno;
use App\Model\Curso;

class MatriculaView
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

    public static function formulario(?string $msg = null, ?Matricula $matricula = null, array $alunos, array $cursos, string $csrfToken): void
    {
        self::displayMessage($msg);
        ?>
        <section>
            <h1><?= isset($matricula) ? "Editar Matrícula" : "Cadastrar Nova Matrícula" ?></h1>
            <form action="<?= isset($matricula) ? "?p=admin_matriculas_edit" : "?p=admin_matriculas_add" ?>" method="post">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
                <?php if ($matricula !== null): ?>
                    <label for="id">Id:</label>
                    <input type="text" name="id" id="id" value="<?= htmlspecialchars($matricula->getId()) ?>" readonly>
                <?php endif; ?>

                <label for="id_aluno">Aluno:</label>
                <select id="id_aluno" name="id_aluno" required>
                    <option value="">Selecione um Aluno</option>
                    <?php foreach ($alunos as $aluno): ?>
                        <option value="<?= htmlspecialchars($aluno->getId()) ?>" <?= ($matricula?->getIdAluno() ?? '') === $aluno->getId() ? 'selected' : '' ?>>
                            <?= htmlspecialchars($aluno->getNome()) ?> (<?= htmlspecialchars($aluno->getCpf()) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>

                <label for="id_curso">Curso:</label>
                <select id="id_curso" name="id_curso" required>
                    <option value="">Selecione um Curso</option>
                    <?php foreach ($cursos as $dadosCurso):
                        $curso = $dadosCurso['curso_obj'];
                        ?>
                        <option value="<?= htmlspecialchars($curso->getId()) ?>" <?= ($matricula?->getIdCurso() ?? '') === $curso->getId() ? 'selected' : '' ?>>
                            <?= htmlspecialchars($curso->getNome()) ?> (R$ <?= number_format($curso->getPreco(), 2, ',', '.') ?>)
                        </option>
                    <?php endforeach; ?>
                </select>

                <label for="data_matricula">Data da Matrícula:</label>
                <input type="datetime-local" id="data_matricula" name="data_matricula"
                    value="<?= isset($matricula) ? date('Y-m-d\TH:i', strtotime($matricula->getDataMatricula())) : date('Y-m-d\TH:i') ?>"
                    required>

                <label for="status">Status:</label>
                <select id="status" name="status">
                    <option value="ativa" <?= ($matricula?->getStatus() ?? '') === 'ativa' ? 'selected' : '' ?>>Ativa</option>
                    <option value="concluida" <?= ($matricula?->getStatus() ?? '') === 'concluida' ? 'selected' : '' ?>>Concluída
                    </option>
                    <option value="cancelada" <?= ($matricula?->getStatus() ?? '') === 'cancelada' ? 'selected' : '' ?>>Cancelada
                    </option>
                </select>

                <button type="submit" name="enviaForm">
                    <?= isset($matricula) ? "Confirmar Edição" : "Salvar Matrícula" ?>
                </button>
                <button type="reset">Limpar</button>
            </form>
            <p><a href="?p=admin_matriculas_list">Voltar para a lista de matrículas</a></p>
        </section>
        <?php
    }

    public static function listar(array $matriculas, ?int $deletarId = null, ?string $msg = null): void
    {
        self::displayMessage($msg);
        if ($deletarId != null) {
            ?>
            <div class="alert">
                Tem certeza que deseja excluir a matrícula com ID #<?= htmlspecialchars($deletarId) ?>?
                <a href="?p=admin_matriculas_delete&confirm_del=<?= $deletarId ?>">Confirmar</a> |
                <a href="?p=admin_matriculas_list">Cancelar</a>
                <span class="close" onclick="this.parentElement.style.display='none'">&times;</span>
            </div>
            <?php
        }
        ?>
        <section>
            <h1>Gerenciar Matrículas</h1>
            <p><a href="?p=admin_matriculas_add">Adicionar Nova Matrícula</a></p>
            <table>
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Aluno</th>
                        <th>Curso</th>
                        <th>Data da Matrícula</th>
                        <th>Status</th>
                        <th>Alterar</th>
                        <th>Deletar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($matriculas)): ?>
                        <tr>
                            <td colspan="7">Nenhuma matrícula cadastrada.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($matriculas as $dados):
                            $matricula = $dados['matricula_obj'];
                            ?>
                            <tr>
                                <td><?= htmlspecialchars($matricula->getId()) ?></td>
                                <td><?= htmlspecialchars($dados['aluno_nome']) ?></td>
                                <td><?= htmlspecialchars($dados['curso_nome']) ?></td>
                                <td><?= htmlspecialchars((new \DateTime($matricula->getDataMatricula()))->format('d/m/Y H:i')) ?></td>
                                <td><?= htmlspecialchars($matricula->getStatus()) ?></td>
                                <td><a href="?p=admin_matriculas_edit&alt=<?= $matricula->getId() ?>">Alterar</a></td>
                                <td><a href="?p=admin_matriculas_delete&del=<?= $matricula->getId() ?>">Deletar</a></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
        <?php
    }
}