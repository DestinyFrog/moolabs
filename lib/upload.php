<?php

require_once dirname(__DIR__) . '/lib/database.php';

function upload($arquivo) {
    $pdo = get_conn();
    
    $diretorio = dirname(__DIR__) . '/assets/images/';

    $tipos_permitidos = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $tamanho_maximo = 5 * 1024 * 1024;
    
    if ($arquivo['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('Erro no upload do arquivo');
    }
    
    if (!in_array($arquivo['type'], $tipos_permitidos)) {
        throw new Exception('Tipo de arquivo não permitido. Use JPEG, PNG, GIF ou WebP');
    }
    
    if ($arquivo['size'] > $tamanho_maximo) {
        throw new Exception('Arquivo muito grande. Máximo 5MB');
    }

    echo "size";
    
    if (!is_dir($diretorio)) {
        mkdir($diretorio, 0755, true);
    }
    
    $extensao = pathinfo($arquivo['name'], PATHINFO_EXTENSION);
    $nome_arquivo = uniqid() . '_' . time() . '.' . $extensao;
    $caminho_completo = $diretorio . $nome_arquivo;
    
    if (!move_uploaded_file($arquivo['tmp_name'], $caminho_completo)) {
        throw new Exception('Erro ao salvar arquivo');
    }
    
    $nome_sem_extensao = pathinfo($arquivo['name'], PATHINFO_FILENAME);
    $alt = 'Imagem ' . $nome_sem_extensao;
    
    $href = '/assets/images/' . $nome_arquivo;

    $stmt = $pdo->prepare("INSERT INTO imagem (href, alt) VALUES (?, ?)");
    $stmt->execute([$href, $alt]);

    echo "insert";
    
    return $pdo->lastInsertId();
}
