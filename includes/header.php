<?php
    require_once "./includes/head.php";
    require_once "./lib/auth.php";

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
            <li><a aria-current="page" href="/produtos.php">Produtos</a></li>
            <li><a aria-current="page" href="/clientes.php">Clientes</a></li>
            <li><a aria-current="page" href="/contato.php">Contato</a></li>
        </ul>

        <ul>
            <li>
                <?php if ($user): ?>
                    <details class="dropdown">
                        <summary role="button">
                            Olá, <?= $user['nome'] ?>
                        </summary>
                        <ul style="max-width: 200px;">
                            <?php if (is_admin()): ?>
                            <li>
                                <a href="/admin/produtos.php">
                                    <button>Menu do Admin</button>
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
                <?php else: ?>
                    <a href="/login.php">
                        <button>Login</button>
                    </a>
                <?php endif; ?>
            </li>
        </ul>
    </nav>
</header>

<?php if ($user)
    include "./includes/usuario_modal.php";