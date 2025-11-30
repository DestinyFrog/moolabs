<?php
include "./includes/header.php"; 
require_once "./lib/database.php"; 

$pdo = get_conn();

$stmt = $pdo->query('SELECT * FROM vw_avaliacao LIMIT 3');
$avaliacoes = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->query('SELECT * FROM vw_avaliacao_produto LIMIT 3');
$avaliacoes_produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->query('SELECT * FROM vw_estatistica_avaliacao');
$avaliacoes_estatisticas = $stmt->fetchAll(PDO::FETCH_ASSOC);

$avaliacao_media = number_format((float) $avaliacoes_estatisticas[0]['media_avaliacao'], 1);
$percentual_recomendacao = (int) $avaliacoes_estatisticas[0]['percentual_recomendacao'];
?>

<main>
    <div class="centered card-grid">
        <article>
            <h2><?= $avaliacao_media ?>%</h2>
            <p>Média de avaliação dos nossos clientes.</p>
        </article>

        <article>
            <h2><?= $percentual_recomendacao ?>%</h2>
            <p>dos clientes recomendariam nossos produtos para familiares e amigos!</p>
        </article>
    </div>

    <hr>

    <h2>Avaliações Gerais</h2>
    <?php foreach ($avaliacoes as $avaliacao)
        include "./includes/card_avaliacao.php"; ?>

    <hr>

    <h2>Comentários sobre Produtos</h2>
    <div class="card-grid">
        <?php foreach ($avaliacoes_produtos as $avaliacao)
           include "./includes/card_avaliacao_produto.php"; ?>
    </div>
</main>

<?php include "./includes/footer.php"; ?>