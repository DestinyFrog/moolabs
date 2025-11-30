<?php
session_start();
require_once dirname(__DIR__) . '/lib/auth.php';
require_once dirname(__DIR__) . '/lib/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET' || !is_admin()) {
    header('Location: /admin/usuarios.php');
    exit;
}

$id = $_GET['id'] ?? null;

if (!$id) {
    $_SESSION['erro'] = 'ID do usuário não informado';
    header('Location: /admin/usuarios.php');
    exit;
}

if ($id == $_SESSION['user_id']) {
    $_SESSION['erro'] = 'Você não pode excluir seu próprio usuário';
    header('Location: /admin/usuarios.php');
    exit;
}

try {
    $pdo = get_conn();

    $stmt = $pdo->prepare("SELECT nome FROM usuario WHERE id = ?");
    $stmt->execute([$id]);
    $usuario = $stmt->fetch();

    if (!$usuario) {
        $_SESSION['erro'] = 'Usuário não encontrado';
        header('Location: /admin/usuarios.php');
        exit;
    }

    $stmt = $pdo->prepare("SELECT COUNT(*) as total_admins FROM usuario WHERE admin = 1");
    $stmt->execute();
    $total_admins = $stmt->fetch()['total_admins'];

    $stmt = $pdo->prepare("SELECT admin FROM usuario WHERE id = ?");
    $stmt->execute([$id]);
    $usuario_admin = $stmt->fetch()['admin'];

    if ($usuario_admin && $total_admins <= 1) {
        $_SESSION['erro'] = 'Não é possível excluir o último administrador do sistema';
        header('Location: /admin/usuarios.php');
        exit;
    }

    $stmt = $pdo->prepare("DELETE FROM usuario WHERE id = ?");
    $stmt->execute([$id]);

    $_SESSION['sucesso'] = 'Usuário excluído com sucesso!';

} catch (Exception $e) {
    $_SESSION['erro'] = 'Erro ao excluir usuário: ' . $e->getMessage();
}

header('Location: /admin/usuarios.php');
exit;