<?php

session_start();
require_once dirname(__DIR__) . '/lib/database.php';
require_once dirname(__DIR__) . '/lib/upload.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /');
    exit;
}

try {
    $pdo = get_conn();

    $id = $_POST['id'] ?? null;
    $nome = $_POST['nome'] ?? null;
    $email = $_POST['email'] ?? null;
    $senha_atual = $_POST['senha_atual'] ?? null;
    $nova_senha = $_POST['nova_senha'] ?? null;
    $confirmar_senha = $_POST['confirmar_senha'] ?? null;

    if (!$id || !$nome || !$email) {
        $_SESSION['erro'] = 'Dados obrigatórios não informados';
        header('Location: /');
        exit;
    }

    $stmt = $pdo->prepare("SELECT id FROM usuario WHERE email = ? AND id != ?");
    $stmt->execute([$email, $id]);
    if ($stmt->fetch()) {
        $_SESSION['erro'] = 'Este email já está em uso por outro usuário';
        header('Location: /');
        exit;
    }

    $imagem_id = null;
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
        $imagem_id = upload($_FILES['imagem']);
    }

    $query = "UPDATE usuario SET nome = ?, email = ?";
    $params = [$nome, $email];

    if ($imagem_id) {
        $query .= ", imagem_id = ?";
        $params[] = $imagem_id;
    }

    if ($senha_atual && $nova_senha && $confirmar_senha) {

        $stmt = $pdo->prepare("SELECT senha FROM usuario WHERE id = ?");
        $stmt->execute([$id]);
        $usuario = $stmt->fetch();

        if (!$usuario || !password_verify($senha_atual, $usuario['senha'])) {
            $_SESSION['erro'] = 'Senha atual incorreta';
            header('Location: /');
            exit;
        }

        if ($nova_senha !== $confirmar_senha) {
            $_SESSION['erro'] = 'Nova senha e confirmação não coincidem';
            header('Location: /');
            exit;
        }

        if (strlen($nova_senha) < 6) {
            $_SESSION['erro'] = 'A nova senha deve ter pelo menos 6 caracteres';
            header('Location: /');
            exit;
        }

        $query .= ", senha = ?";
        $params[] = password_hash($nova_senha, PASSWORD_DEFAULT);
    }

    $query .= " WHERE id = ?";
    $params[] = $id;

    $stmt = $pdo->prepare($query);
    $stmt->execute($params);

    $_SESSION['sucesso'] = 'Perfil atualizado com sucesso!';

} catch (Exception $e) {
    $_SESSION['erro'] = 'Erro ao atualizar perfil: ' . $e->getMessage();
}

header('Location: /');
exit;
