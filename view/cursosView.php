<?php
namespace App\View;

class CursosView
{
    public static function listar(array $cursos): void
    {
        ?>
        <section>
            <h1>Todos os Cursos Disponíveis</h1>
            <p>Explore nossa vasta gama de cursos e encontre o ideal para você.</p>
            <table>
                <thead>
                    <tr>
                        <th>Nome do Curso</th>
                        <th>Descrição</th>
                        <th>Duração (horas)</th>
                        <th>Preço</th>
                        <th>Status</th>
                        <th>Alunos Matriculados</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($cursos)): ?>
                        <tr>
                            <td colspan="6">Nenhum curso disponível no momento.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($cursos as $dadosCurso): ?>
                            <tr>
                                <td><?= htmlspecialchars($dadosCurso['curso_obj']->getNome()) ?></td>
                                <td><?= htmlspecialchars($dadosCurso['curso_obj']->getDescricao()) ?></td>
                                <td><?= htmlspecialchars($dadosCurso['curso_obj']->getDuracao()) ?></td>
                                <td>R$ <?= number_format($dadosCurso['curso_obj']->getPreco(), 2, ',', '.') ?></td>
                                <td><?= htmlspecialchars($dadosCurso['curso_obj']->getStatus()) ?></td>
                                <td><?= htmlspecialchars($dadosCurso['total_alunos_matriculados'] ?? 0) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
        <?php
    }
}