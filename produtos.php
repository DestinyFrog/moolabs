<?php
include './lib/database.php';

$pdo = get_conn();

$produtos = [];

if (isset($_GET['tipo'])) {
    $tipo = $_GET['tipo'];

    $stmt = $pdo->prepare("SELECT * FROM vw_produto WHERE tipo_alimento_slug = ?");
    $stmt->bindParam(1, $tipo);
    $stmt->execute();
} else {
    $stmt = $pdo->query("SELECT * FROM vw_produto");
}

$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

$tipos_alimentos = $pdo->query("SELECT slug, nome FROM tipo_alimento");
?>

<?php include "./includes/header.php"; ?>

<main>
    <article>
        <h2>Nossos Produtos Premium</h2>
        <p>Descubra nossa linha completa de queijos artesanais e laticínios especiais, produzidos com tecnologia de ponta e ingredientes selecionados.</p>
    </article>

    <div role="group">
        <button aria-current="true">
            <a href="/produtos.php">Tudo</a>
        </button>

        <?php foreach ($tipos_alimentos as $tipo_alimento): ?>
            <button aria-current="false">
                <a class="secondary" href="/produtos.php?tipo=<?= $tipo_alimento["slug"] ?>">
                    <?= $tipo_alimento["nome"] ?>
                </a>
            </button>
        <?php endforeach; ?>
    </div>

    <div class="card-grid">
        <?php foreach ($produtos as $produto)
            include "includes/card_produto.php"; ?>
    </div>
</main>

<?php include "./includes/footer.php"; ?>