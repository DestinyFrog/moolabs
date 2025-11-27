<?php include "./includes/head.php"; ?>

<main>
    <article style="margin-top: 60px;">
        <form method="post" action="/login.php">
            <h1>Login</h1>

            <fieldset>
                <label>
                    Email
                    <input
                        type="email"
                        name="email"
                        placeholder="email@email.com"
                        autocomplete="given-name"
                    />
                </label>

                <label>
                    Senha
                    <input
                        type="password"
                        name="senha"
                        placeholder="******"
                        autocomplete="senha"
                    />
                </label>
            </fieldset>
        
            <input
                type="submit"
                value="Login"
            />
        </form>
    </article>
</main>

<?php include "./includes/foot.php"; ?>