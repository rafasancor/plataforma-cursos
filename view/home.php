<section>
    <h1>Bem-vindo à Plataforma de Cursos Online!</h1>
    <p>Descubra uma variedade de cursos para impulsionar sua carreira e conhecimentos.</p>

    <h2>Aprenda no seu ritmo</h2>
    <p>Estude de onde quiser, quando quiser. Nosso conteúdo é adaptável ao seu estilo de vida e nível de conhecimento.</p>

    <h2>Cursos em Destaque</h2>
    <?php

    ?>
    <div style="display: flex; flex-wrap: wrap; gap: 20px;">
        <?php if (!empty($featuredCourses)): ?>
            <?php foreach ($featuredCourses as $courseData):
                $course = $courseData['curso_obj'];
                $alunosMatriculados = $courseData['total_alunos_matriculados'] ?? 0;
                ?>
                <div style="border: 1px solid #ccc; padding: 15px; width: 300px; border-radius: 8px;">
                    <h3><?= htmlspecialchars($course->getNome()) ?></h3>
                    <p><?= htmlspecialchars($course->getDescricao()) ?></p>
                    <p><strong>Preço:</strong> R$ <?= number_format($course->getPreco(), 2, ',', '.') ?></p>
                    <p><small>Alunos matriculados: <?= htmlspecialchars($alunosMatriculados) ?></small></p>
                    <a href="?p=cursos"
                        style="background-color: #007bff; color: white; padding: 8px 12px; text-decoration: none; border-radius: 5px;">Ver
                        Detalhes</a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Nenhum curso em destaque disponível no momento.</p>
        <?php endif; ?>
    </div>
</section>