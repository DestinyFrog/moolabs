<?php require_once "./lib/functions.php"; ?>

<article>
    <header>
        <h2><?= $produto['nome'] ?></h2>
    </header>

    <img src="<?= $produto['imagem_href'] ?>"
         alt="<?= $produto['imagem_alt'] ?>"
         class="card-image" />

    <footer>
        <p><?= $produto['descricao'] ?></p>
        <h3><?= formata_dinheiro($produto['preco']) ?></h3>
    </footer>
</article>