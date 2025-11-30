<?php
require_once dirname(__DIR__) . '/includes/header_admin.php';
require_once dirname(__DIR__) . '/lib/auth.php';
require_once dirname(__DIR__) . '/lib/database.php';

if (!is_admin()) {
    header('Location: /');
    exit;
}

$pdo = get_conn();
$produtos = $pdo->query("
    SELECT p.*, i.href as imagem_href, i.alt as imagem_alt, ta.nome as tipo_nome 
    FROM produto p 
    LEFT JOIN imagem i ON p.imagem_id = i.id 
    INNER JOIN tipo_alimento ta ON p.tipo_alimento_id = ta.id
    ORDER BY p.nome
")->fetchAll(PDO::FETCH_ASSOC);

$tipos = $pdo->query("SELECT id, nome FROM tipo_alimento")->fetchAll(PDO::FETCH_ASSOC);
?>

<main class="container">
    <header>
        <hgroup>
            <h1>Gerenciar Produtos</h1>
        </hgroup>

        <nav>
            <button class="outline" onclick="document.getElementById('modal-criar-produto').showModal()">
                + Novo Produto
            </button>
        </nav>
    </header>

    <table>
        <thead>
            <tr>
                <th>Imagem</th>
                <th>Nome</th>
                <th>Tipo</th>
                <th>Preço</th>
                <th>Tags</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($produtos as $produto): ?>
            <tr>
                <td>
                    <?php if ($produto['imagem_href']): ?>
                        <img src="<?= $produto['imagem_href'] ?>" alt="<?= $produto['imagem_alt'] ?>" 
                             style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                    <?php else: ?>
                        <span>Sem imagem</span>
                    <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($produto['nome']) ?></td>
                <td><?= htmlspecialchars($produto['tipo_nome']) ?></td>
                <td>R$ <?= number_format($produto['preco'], 2, ',', '.') ?></td>
                <td>
                    <?php 
                    $tags = json_decode($produto['tags'] ?? '[]', true);
                    foreach ($tags as $tag): 
                    ?>
                        <mark><?= htmlspecialchars($tag) ?></mark>
                    <?php endforeach; ?>
                </td>
                <td>
                    <div role="group">
                        <button class="outline" onclick="abrirEditarProduto(<?= htmlspecialchars(json_encode($produto)) ?>)">
                            Editar
                        </button>
                        <button class="secondary" 
                                onclick="confirmarExclusao(<?= $produto['id'] ?>, '<?= htmlspecialchars($produto['nome']) ?>')">
                            Excluir
                        </button>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <dialog id="modal-criar-produto">
        <article>
            <header>
                <button aria-label="Close" rel="prev" onclick="this.closest('dialog').close()"></button>
                <h3>Novo Produto</h3>
            </header>
            <form method="post" action="/crud/cria_produto.php" enctype="multipart/form-data">
                <fieldset>
                    <label>
                        Nome
                        <input type="text" name="nome" placeholder="Nome do produto" required>
                    </label>

                    <label>
                        Descrição
                        <textarea name="descricao" placeholder="Descrição do produto" required></textarea>
                    </label>

                    <label>
                        Preço
                        <input type="number" name="preco" step="0.01" min="0" placeholder="0.00" required>
                    </label>

                    <label>
                        Tipo de Alimento
                        <select name="tipo_alimento_id" required>
                            <option value="">Selecione um tipo</option>
                            <?php foreach ($tipos as $tipo): ?>
                                <option value="<?= $tipo['id'] ?>"><?= htmlspecialchars($tipo['nome']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </label>

                    <label>
                        Tags (separadas por vírgula)
                        <input type="text" name="tags" placeholder="premium, artesanal, exclusivo">
                    </label>

                    <label>
                        Imagem do Produto
                        <input type="file" name="imagem" accept="image/*">
                    </label>
                </fieldset>

                <footer style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 2rem;">
                    <button type="button" class="secondary" onclick="this.closest('dialog').close()">
                        Cancelar
                    </button>
                    <button type="submit">
                        Criar Produto
                    </button>
                </footer>
            </form>
        </article>
    </dialog>

    <dialog id="modal-editar-produto">
        <article>
            <header>
                <button aria-label="Close" rel="prev" onclick="this.closest('dialog').close()"></button>
                <h3>Editar Produto</h3>
            </header>
            <form method="post" action="/crud/edita_produto.php" enctype="multipart/form-data">
                <input type="hidden" name="id" id="editar-id">
                
                <fieldset>
                    <label>
                        Nome
                        <input type="text" name="nome" id="editar-nome" required>
                    </label>

                    <label>
                        Descrição
                        <textarea name="descricao" id="editar-descricao" required></textarea>
                    </label>

                    <label>
                        Preço
                        <input type="number" name="preco" id="editar-preco" step="0.01" min="0" required>
                    </label>

                    <label>
                        Tipo de Alimento
                        <select name="tipo_alimento_id" id="editar-tipo" required>
                            <?php foreach ($tipos as $tipo): ?>
                                <option value="<?= $tipo['id'] ?>"><?= htmlspecialchars($tipo['nome']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </label>

                    <label>
                        Tags (separadas por vírgula)
                        <input type="text" name="tags" id="editar-tags" placeholder="premium, artesanal, exclusivo">
                    </label>

                    <label>
                        Imagem do Produto
                        <input type="file" name="imagem" accept="image/*">
                        <small>Deixe em branco para manter a imagem atual</small>
                    </label>
                </fieldset>

                <footer style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 2rem;">
                    <button type="button" class="secondary" onclick="this.closest('dialog').close()">
                        Cancelar
                    </button>
                    <button type="submit">
                        Salvar Alterações
                    </button>
                </footer>
            </form>
        </article>
    </dialog>
</main>

<script>
function abrirEditarProduto(produto) {
    document.getElementById('editar-id').value = produto.id;
    document.getElementById('editar-nome').value = produto.nome;
    document.getElementById('editar-descricao').value = produto.descricao;
    document.getElementById('editar-preco').value = produto.preco;
    document.getElementById('editar-tipo').value = produto.tipo_alimento_id;
    
    const tags = JSON.parse(produto.tags || '[]');
    document.getElementById('editar-tags').value = tags.join(', ');
    
    document.getElementById('modal-editar-produto').showModal();
}

function confirmarExclusao(id, nome) {
    if (confirm(`Tem certeza que deseja excluir o produto "${nome}"?`)) {
        window.location.href = `/crud/deleta_produto.php?id=${id}`;
    }
}
</script>

<?php include dirname(__DIR__) . "/includes/foot.php"; ?>