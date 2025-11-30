<?php

session_start();
require_once dirname(__DIR__) . '/lib/database.php';
require_once dirname(__DIR__) . '/lib/upload.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /cadastro.php');
    exit;
}

try {
    $pdo = get_conn();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $nome = $_POST['nome'] ?? null;
    $email = $_POST['email'] ?? null;
    $senha = $_POST['senha'] ?? null;

    if (!$nome || !$email || !$senha) {
        $_SESSION['erro'] = 'Todos os campos são obrigatórios';
        header('Location: /cadastro.php');
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['erro'] = 'Email inválido';
        header('Location: /cadastro.php');
        exit;
    }

    if (strlen($senha) < 6) {
        $_SESSION['erro'] = 'A senha deve ter pelo menos 6 caracteres';
        header('Location: /cadastro.php');
        exit;
    }

    $stmt = $pdo->prepare("SELECT id FROM usuario WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        $_SESSION['erro'] = 'Este email já está cadastrado';
        header('Location: /cadastro.php');
        exit;
    }

    $imagem_id = null;
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
        $imagem_id = upload($_FILES['imagem']);
    }

    $stmt = $pdo->prepare("INSERT INTO usuario (nome, email, senha, imagem_id) VALUES (?, ?, ?, ?)");
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
    $stmt->execute([$nome, $email, $senha_hash, $imagem_id]);

    $_SESSION['sucesso'] = 'Usuário cadastrado com sucesso! Faça login para continuar.';

} catch (Exception $e) {
    $_SESSION['erro'] = 'Erro ao cadastrar usuário: ' . $e->getMessage();
    header('Location: /cadastro.php');
    exit;
}