<?php include "./includes/head.php"; ?>

<main>
    <article style="margin-top: 60px;">
        <form method="post" action="/crud/cria_usuario.php" enctype="multipart/form-data">
            <fieldset>
                <label>
                    Nome
                    <input
                        type="text"
                        name="nome"
                        placeholder="Seu nome completo"
                        required
                    />
                </label>

                <label>
                    Email
                    <input
                        type="email"
                        name="email"
                        placeholder="email@email.com"
                        required
                    />
                </label>

                <label>
                    Senha
                    <input
                        type="password"
                        name="senha"
                        placeholder="******"
                        required
                        minlength="6"
                    />
                </label>

                <label>
                    Foto de perfil (opcional)
                    <input
                        type="file"
                        name="imagem"
                        accept="image/*"
                    />
                </label>
            </fieldset>
        
            <input
                type="submit"
                value="Cadastrar"
            />

            <p style="text-align: center; margin-top: 1rem;">
                Já tem uma conta? <a href="/login.php">Faça login</a>
            </p>
        </form>
    </article>
</main>

<?php include "./includes/foot.php"; ?>