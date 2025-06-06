<section>
    <h1>Bem-vindo à Plataforma de Cursos Online!</h1>
    <p>Descubra uma variedade de cursos para impulsionar sua carreira e conhecimentos.</p>

    <h2>Aprenda no seu ritmo</h2>
    <p>Estude de onde quiser, quando quiser. Nosso conteúdo é adaptável ao seu estilo de vida e nível de conhecimento.</p>

    <h2>Cursos em Destaque</h2>
    <?php

    ?>
    <div id="sectionCard">
        <?php if (!empty($featuredCourses)): ?>
            <?php foreach ($featuredCourses as $courseData):
                $course = $courseData['curso_obj'];
                $alunosMatriculados = $courseData['total_alunos_matriculados'] ?? 0;
                ?>
                <div id="cards">
                    <h3><?= htmlspecialchars($course->getNome()) ?></h3>
                    <p><?= htmlspecialchars($course->getDescricao()) ?></p>
                    <p><strong>Preço:</strong> R$ <?= number_format($course->getPreco(), 2, ',', '.') ?></p>
                    <p><small>Alunos matriculados: <?= htmlspecialchars($alunosMatriculados) ?></small></p>
                    <a href="?p=cursos" id="btnCard">Ver Detalhes</a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Nenhum curso em destaque disponível no momento.</p>
        <?php endif; ?>
    </div>
</section>