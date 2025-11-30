<?php
session_start();
require_once dirname(__DIR__) . '/lib/auth.php';
require_once dirname(__DIR__) . '/lib/database.php';
require_once dirname(__DIR__) . '/lib/upload.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST'/* || !is_admin() */) {
    header('Location: /produtos.php');
    exit;
}

try {
    $pdo = get_conn();

    $nome = $_POST['nome'] ?? null;
    $descricao = $_POST['descricao'] ?? null;
    $preco = $_POST['preco'] ?? null;
    $tipo_alimento_id = $_POST['tipo_alimento_id'] ?? null;
    $tags = $_POST['tags'] ?? '';

    if (!$nome || !$descricao || !$preco || !$tipo_alimento_id) {
        $_SESSION['erro'] = 'Todos os campos obrigatórios devem ser preenchidos';
        header('Location: /produtos.php');
        exit;
    }

    $tags_array = [];
    if (!empty($tags)) {
        $tags_array = array_map('trim', explode(',', $tags));
        $tags_array = array_filter($tags_array);
    }

    $imagem_id = null;
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
        $imagem_id = upload($_FILES['imagem']);
    }

    $stmt = $pdo->prepare("
        INSERT INTO produto (nome, descricao, preco, tipo_alimento_id, tags, imagem_id) 
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    
    $stmt->execute([
        $nome,
        $descricao,
        $preco,
        $tipo_alimento_id,
        json_encode($tags_array),
        $imagem_id
    ]);
    echo "produto";

    $_SESSION['sucesso'] = 'Produto criado com sucesso!';

} catch (Exception $e) {
    echo $e->getMessage();
    $_SESSION['erro'] = 'Erro ao criar produto: ' . $e->getMessage();
    exit;
}

header('Location: /admin/produtos.php');
exit;