<?php
    require_once dirname(__DIR__) . "/includes/head.php";
    require_once dirname(__DIR__) . "/lib/auth.php";

    $user = get_user();
?>

<header class="header centered">
    <hgroup>
        <h1>
            <a class="contrast" href="/">
                Moolabs
            </a>
        </h1>
        <p>Laticínios de Alta Qualidade.</p>
    </hgroup>

    <nav>
        <ul>
            <li><a aria-current="page" href="/admin/usuarios.php">Usuários</a></li>
            <li><a aria-current="page" href="/admin/produtos.php">Produtos</a></li>
        </ul>

        <ul>
            <li>
                <details class="dropdown">
                    <summary role="button">
                        Olá, <?= $user['nome'] ?>
                    </summary>
                    <ul style="max-width: 200px;">
                        <?php if (is_admin()): ?>
                        <li>
                            <a href="/">
                                <button>Retornar a Página</button>
                            </a>
                        </li>
                        <?php endif; ?>

                        <li>
                            <button onclick="toggle_modal_usuario()">
                                Editar Conta
                            </button>
                        </li>

                        <li>
                            <a href="/logout.php">
                                <button>Sair da Conta</button>
                            </a>
                        </li>
                    </ul>
                </details>
            </li>
        </ul>
    </nav>
</header>

<?php if ($user)
    include "./includes/usuario_modal.php";