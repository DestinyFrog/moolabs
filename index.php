<?php
require_once './lib/database.php';

$pdo = get_conn();
$produtos = $pdo->query("SELECT * FROM vw_produto LIMIT 4");
?>

<?php require_once "./includes/header.php"; ?>

<main>
    <article>
        <h4>Queijos artesanais e leites especiais produzidos com excelência e tradição familiar</h4>

        <p>Na MooLabs, combinamos tradição familiar com alta tecnologia para produzir os melhores laticínios do Brasil.
        </p>
        <p>Nossos produtos são cuidadosamente selecionados e processados para garantir máxima qualidade e sabor.</p>
        <p>Comprometidos com o meio ambiente e bem-estar animal, todos os nossos produtos seguem rigorosos padrões de
            sustentabilidade e são certificados por órgãos reconhecidos internacionalmente.</p>
    </article>

    <h2>Produtos</h2>

    <div class="card-grid">
        <?php foreach ($produtos as $produto)
            include "./includes/card_produto.php"; ?>
    </div>
</main>

<?php require_once "./includes/footer.php"; ?>