<?php
require_once "./lib/auth.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrf_token = $_POST['csrf_token'] ?? '';
    
    if (!verify_csrf_token($csrf_token)) {
        $error = "Token de segurança inválido";
    } else {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        
        $result = login($email, $password);
        
        if ($result['success']) {
            header('Location: index.php');
            exit();
        } else {
            $error = $result['message'];
        }
    }
}
?>

<?php include "./includes/head.php"; ?>

<main style="margin-top: 40px;">
    <article>
        <form method="POST">
            <h1>Login</h1>

            <input type="hidden" name="csrf_token" value="<?= generate_csrf_token() ?>">
            
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Senha" required>
            
            <button type="submit">Login</button>
            
            <?php if (isset($error)): ?>
                <div class="error"><?php echo $error; ?></div>
            <?php endif; ?>
        </form>

        <a href="/cadastro.php">Ainda não tem conta? Crie uma ...</a>
    </article>
</main>

<?php include "./includes/foot.php"; ?>