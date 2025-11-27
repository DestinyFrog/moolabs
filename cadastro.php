<?php
include "./lib/database.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST["nome"];
    $email = $_POST["email"];
    $senha = $_POST["senha"];

    try {
        $conn = get_conn();
        $stmt = $conn->prepare("INSERT INTO usuario (usuario, email, senha) VALUES(?, ?, ?)");
        $stmt->bindParam(1, $nome);
        $stmt->bindParam(2, $email);
        $stmt->bindParam(2, $senha);
        $stmt->execute();

        header("Location: /login.php", true, 201);
        exit;
    }
    catch (\Exception $err) {

    }
} 
?>

<?php include "./includes/head.php"; ?>

<main>
    <article style="margin-top: 60px;">
        <form method="post" action="/cadastro.php">
            <fieldset>
                <label>
                    Nome
                    <input
                        type="nome"
                        name="nome"
                        placeholder="Nome"
                    />
                </label>

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