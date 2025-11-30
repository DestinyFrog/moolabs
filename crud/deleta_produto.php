<?php
session_start();
require_once dirname(__DIR__) . '/lib/auth.php';
require_once dirname(__DIR__) . '/lib/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET' || !is_admin()) {
    header('Location: /produtos.php');
    exit;
}

$id = $_GET['id'] ?? null;

if (!$id) {
    $_SESSION['erro'] = 'ID do produto não informado';
    header('Location: /produtos.php');
    exit;
}

echo "inicio";

try {
    $pdo = get_conn();

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM avaliacao_produto WHERE produto_id = ?");
    $stmt->execute([$id]);
    $tem_avaliacoes = $stmt->fetchColumn() > 0;
    echo "avaliacoes";

    if ($tem_avaliacoes) {
        $stmt = $pdo->prepare("DELETE FROM avaliacao_produto WHERE produto_id = ?");
        $stmt->execute([$id]);
    }

    $stmt = $pdo->prepare("DELETE FROM produto WHERE id = ?");
    $stmt->execute([$id]);
    echo "produto";

    $_SESSION['sucesso'] = 'Produto excluído com sucesso!';

} catch (Exception $e) {
    $_SESSION['erro'] = 'Erro ao excluir produto: ' . $e->getMessage();
}

header('Location: /admin/produtos.php');
exit;