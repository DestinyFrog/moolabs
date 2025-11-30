<?php
include "./includes/header.php";

$redes = [
    "instagram" => [
        "imagem_alt" => "icon-instagram",
        "imagem_src" => "/assets/instagram.svg",
        "conta" => "@moolabs",
        "tema" => "danger",
        "descricao" => "Fotos dos nossos produtos, bastidores da produção e receitas exclusivas.",
        "dados" => [
            "seguidores" => "25.4K",
            "posts" => "1.2K",
            "engajamento" => "4.8%"
        ],
        "texto_botao" => "Seguir no Instagram"
    ],

    "facebook" => [
        "imagem_alt" => "icon-facebook",
        "imagem_src" => "/assets/facebook.svg",
        "conta" => "Moolabs Laticínios",
        "tema" => "primary",
        "descricao" => "Novidades da empresa, eventos e interação com a comunidade.",
    "dados" => [
            "curtidas" => "18.7K",
            "posts" => "892K",
            "engajamento" => "3.2%"
        ],
        "texto_botao" => "Curtir no Facebook"
    ],

    "youtube" => [
        "imagem_alt" => "icon-youtube",
        "imagem_src" => "/assets/youtube.svg",
        "conta" => "Moolabs TV",
        "tema" => "danger",
        "descricao" => "Documentários sobre produção, receitas e visitas à fazenda.",
        "dados" => [
            "inscritos" => "12.3K",
            "vídeos" => "156",
            "visualizações" => "2.1M"
        ],
        "texto_botao" => "Inscrever-se"
    ],

    "linkedin" => [
        "imagem_alt" => "icon-linkedin",
        "imagem_src" => "/assets/linkedin.svg",
        "conta" => "Moolabs Laticínios Premium",
        "tema" => "primary",
        "descricao" => "Conteúdo corporativo, vagas de emprego e networking profissional.",
        "dados" => [
            "seguidores" => "5.2K",
            "funcionários" => "340",
        ],
        "texto_botao" => "Seguir no Linkedin"
    ],

    "discord" => [
        "imagem_alt" => "icon-discord",
        "imagem_src" => "/assets/discord.svg",
        "conta" => "Comunidade MooLabs",
        "tema" => "primary",
        "descricao" => "Comunidade de entusiastas e chefs.",
        "dados" => [
            "membros" => "517",
            "funcionários" => "340",
        ],
        "texto_botao" => "Entrar"
    ],

    "whatsapp business" => [
        "imagem_alt" => "icon-whatsapp",
        "imagem_src" => "/assets/whatsapp.svg",
        "conta" => "(18) 99876-5432",
        "tema" => "success",
        "descricao" => "Atendimento 24h com resposta em até 2 horas.",
        "texto_botao" => "Conversar"
    ]
];
?>

<main class="card-grid">
    <?php foreach ($redes as $rede_nome => $rede) : ?>
        <article>
            <header style="display: flex; justify-content: space-between; flex-direction: row;">
                <img class="icon" alt="<?= $rede['imagem_alt'] ?>" src="<?= $rede['imagem_src'] ?>" />
                <h2 class="centered" style="width: 100%;"><?= ucfirst($rede_nome) ?></h2>
            </header>

            <main>
                <h4 class="centered"><?= $rede['conta'] ?></h4>
                <p class="centered"><?= $rede['descricao'] ?></p>

                <table>
                    <tbody>
                        <?php foreach ($rede['dados'] as $dado_nome => $dado_valor) : ?>
                            <tr>
                                <th><?= ucfirst($dado_nome) ?></th>
                                <td><?= $dado_valor ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </main>
        </article>
    <?php endforeach; ?>
</main>

<?php include "./includes/footer.php"; ?>