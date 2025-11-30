<?php
require_once dirname(__DIR__) . '/includes/header_admin.php';
require_once dirname(__DIR__) . '/lib/auth.php';
require_once dirname(__DIR__) . '/lib/database.php';

if (!is_admin()) {
    header('Location: /');
    exit;
}

$pdo = get_conn();
$usuarios = $pdo->query("
    SELECT u.*, i.href as usuario_imagem_href, i.alt as usuario_imagem_alt 
    FROM usuario u 
    LEFT JOIN imagem i ON u.imagem_id = i.id 
    ORDER BY u.nome
")->fetchAll(PDO::FETCH_ASSOC);
?>

<main class="container">
    <header>
        <hgroup>
            <h1>Gerenciar Usuários</h1>
            <p>Administre os usuários do sistema</p>
        </hgroup>
    </header>

    <table>
        <thead>
            <tr>
                <th>Foto</th>
                <th>Nome</th>
                <th>Email</th>
                <th>Tipo</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($usuarios as $usuario): ?>
            <tr>
                <td>
                    <?php if ($usuario['usuario_imagem_href']): ?>
                        <img src="<?= $usuario['usuario_imagem_href'] ?>" 
                             alt="<?= $usuario['usuario_imagem_alt'] ?>" 
                             style="width: 40px; height: 40px; object-fit: cover; border-radius: 50%;">
                    <?php else: ?>
                        <div style="width: 40px; height: 40px; border-radius: 50%; background: #ccc; display: flex; align-items: center; justify-content: center;">
                            <small>Sem foto</small>
                        </div>
                    <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($usuario['nome']) ?></td>
                <td><?= htmlspecialchars($usuario['email']) ?></td>
                <td>
                    <?php if ($usuario['admin']): ?>
                        <span style="color: #e74c3c; font-weight: bold;">Administrador</span>
                    <?php else: ?>
                        <span style="color: #7f8c8d;">Usuário</span>
                    <?php endif; ?>
                </td>
                <td>
                    <div role="group">
                        <?php if ($usuario['id'] != $_SESSION['user_id']): ?>
                            <button class="secondary" 
                                    onclick="confirmarExclusao(<?= $usuario['id'] ?>, '<?= htmlspecialchars($usuario['nome']) ?>')">
                                Excluir
                            </button>
                        <?php else: ?>
                            <button disabled title="Não é possível excluir seu próprio usuário">
                                Excluir
                            </button>
                        <?php endif; ?>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <dialog id="modal-confirmar-exclusao">
        <article>
            <header>
                <button aria-label="Close" rel="prev" onclick="this.closest('dialog').close()"></button>
                <h3>Confirmar Exclusão</h3>
            </header>
            <p id="mensagem-exclusao">Tem certeza que deseja excluir este usuário?</p>
            <footer>
                <button class="secondary" onclick="this.closest('dialog').close()">Cancelar</button>
                <button id="btn-confirmar-exclusao" class="contrast">Excluir</button>
            </footer>
        </article>
    </dialog>
</main>

<script>
let usuarioParaExcluir = null;

function confirmarExclusao(id, nome) {
    usuarioParaExcluir = id;
    const modal = document.getElementById('modal-confirmar-exclusao');
    const mensagem = document.getElementById('mensagem-exclusao');
    
    mensagem.textContent = `Tem certeza que deseja excluir o usuário "${nome}"? Esta ação não pode ser desfeita.`;
    modal.showModal();
}

// Configurar botão de confirmação
document.getElementById('btn-confirmar-exclusao').addEventListener('click', function() {
    if (usuarioParaExcluir) {
        window.location.href = `/crud/deleta_usuario.php?id=${usuarioParaExcluir}`;
    }
});

// Fechar modal com ESC
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        const modal = document.getElementById('modal-confirmar-exclusao');
        if (modal.open) {
            modal.close();
        }
    }
});
</script>

<?php include dirname(__DIR__) . "/includes/foot.php"; ?>