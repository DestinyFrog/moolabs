
<article>
    <header style="display: flex; justify-content: center;">
        <?php if ($avaliacao['cliente_imagem_href']): ?>
            <img class="usuario-imagem"
                src="<?= $avaliacao['cliente_imagem_href'] ?>"
                alt="<?= $avaliacao['cliente_imagem_alt'] ?>" />

            <div style="margin: 0px 20px;">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="1.5" stroke="red" class="size-6" width="40px">
                    <path fill="red" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                </svg>
            </div>
        <?php endif; ?>

        <img class="usuario-imagem"
            src="<?= $avaliacao['produto_imagem_href'] ?>"
            alt="<?= $avaliacao['produto_imagem_alt'] ?>" />
    </header>

    <main>
        <h3><?= $avaliacao['produto_nome'] ?></h3>
        <hr>
        <p><?= $avaliacao['produto_descricao'] ?></p>

        <blockquote>
            "<?= $avaliacao['descricao'] ?>"
            <br/><br/>
            <cite> - 
                <?php
                if ($avaliacao['cliente_sexo'] === 'F')
                    echo $avaliacao['cliente_profissao_prefixo_feminino'];
                else if ($avaliacao['cliente_sexo'] === 'M')
                    echo $avaliacao['cliente_profissao_prefixo_masculino'];
                ?>
                <?= $avaliacao['cliente_nome'] ?>
            </cite>
        </blockquote>
    </main>
</article>