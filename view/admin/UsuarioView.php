<?php
namespace App\View\Admin;

use App\Model\Usuario;

class UsuarioView
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

    public static function formulario(?string $msg = null, ?Usuario $usuario = null, string $csrfToken): void
    {
        self::displayMessage($msg);
        ?>
        <section>
            <h1><?= isset($usuario) ? "Editar Usuário Administrador" : "Cadastrar Novo Usuário Administrador" ?></h1>
            <form action="<?= isset($usuario) ? "?p=admin_usuarios_edit" : "?p=admin_usuarios_add" ?>" method="post">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
                <?php if ($usuario !== null): ?>
                    <label for="id">Id:</label>
                    <input type="text" name="id" id="id" value="<?= htmlspecialchars($usuario->getId()) ?>" readonly>
                <?php endif; ?>

                <label for="email">E-mail:</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($usuario?->getEmail() ?? "") ?>"
                    required>

                <label for="senha"><?= isset($usuario) ? "Nova Senha (deixe em branco para não alterar)" : "Senha" ?>:</label>
                <input type="password" id="senha" name="senha" <?= isset($usuario) ? '' : 'required' ?> minlength="6">

                <label for="role">Papel:</label>
                <select id="role" name="role" required>
                    <option value="user" <?= ($usuario?->getRole() ?? '') === 'user' ? 'selected' : '' ?>>Usuário Comum</option>
                    <option value="admin" <?= ($usuario?->getRole() ?? '') === 'admin' ? 'selected' : '' ?>>Administrador</option>
                </select>

                <button type="submit" name="enviaForm">
                    <?= isset($usuario) ? "Confirmar Edição" : "Salvar Usuário" ?>
                </button>
                <button type="reset">Limpar</button>
            </form>
            <p><a href="?p=admin_usuarios_list">Voltar para a lista de usuários</a></p>
        </section>
        <?php
    }

    public static function listar(array $usuarios, ?int $deletarId = null, ?string $msg = null): void
    {
        self::displayMessage($msg);
        if ($deletarId != null) {
            ?>
            <div class="alert">
                Tem certeza que deseja excluir o usuário com ID #<?= htmlspecialchars($deletarId) ?>?
                <a href="?p=admin_usuarios_delete&confirm_del=<?= $deletarId ?>">Confirmar</a> |
                <a href="?p=admin_usuarios_list">Cancelar</a>
                <span class="close" onclick="this.parentElement.style.display='none'">&times;</span>
            </div>
            <?php
        }
        ?>
        <section>
            <h1>Gerenciar Usuários Administradores</h1>
            <p><a href="?p=admin_usuarios_add">Adicionar Novo Usuário Administrador</a></p>
            <table>
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>E-mail</th>
                        <th>Papel</th>
                        <th>Alterar</th>
                        <th>Deletar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($usuarios)): ?>
                        <tr>
                            <td colspan="5">Nenhum usuário administrador cadastrado.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($usuarios as $usuario): ?>
                            <tr>
                                <td><?= htmlspecialchars($usuario->getId()) ?></td>
                                <td><?= htmlspecialchars($usuario->getEmail()) ?></td>
                                <td><?= htmlspecialchars($usuario->getRole()) ?></td>
                                <td><a href="?p=admin_usuarios_edit&alt=<?= $usuario->getId() ?>">Alterar</a></td>
                                <td><a href="?p=admin_usuarios_delete&del=<?= $usuario->getId() ?>">Deletar</a></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
        <?php
    }
}