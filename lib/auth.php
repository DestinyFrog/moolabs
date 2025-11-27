<?php

session_start();

/**
 * Verifica se o usuário está logado
 */
function is_logged_in() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Realiza o login do usuário
 */
function login($email, $password) {
    // Validar entrada
    if (empty($email) || empty($password)) {
        return array('success' => false, 'message' => 'Email e senha são obrigatórios');
    }

    // Buscar usuário no banco de dados
    //todo: Implementar consulta ao banco de dados
    $user = null; // Substituir pela consulta: SELECT * FROM users WHERE email = ?

    if (!$user) {
        return array('success' => false, 'message' => 'Usuário não encontrado');
    }

    //todo: Verificar senha (depende de como a senha está armazenada)
    $password_valid = false; // Substituir pela verificação: password_verify($password, $user['password'])
    
    if (!$password_valid) {
        return array('success' => false, 'message' => 'Senha incorreta');
    }

    // Configurar sessão
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_email'] = $user['email'];
    $_SESSION['user_name'] = $user['name'];
    $_SESSION['logged_in'] = true;

    return array('success' => true, 'message' => 'Login realizado com sucesso');
}

/**
 * Realiza o logout do usuário
 */
function logout() {
    // Limpar dados da sessão
    $_SESSION = array();

    // Destruir sessão
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    session_destroy();

    return array('success' => true, 'message' => 'Logout realizado com sucesso');
}

/**
 * Registra um novo usuário
 */
function register($name, $email, $password, $confirm_password) {
    // Validar entrada
    if (empty($name) || empty($email) || empty($password)) {
        return array('success' => false, 'message' => 'Todos os campos são obrigatórios');
    }

    if ($password !== $confirm_password) {
        return array('success' => false, 'message' => 'As senhas não coincidem');
    }

    if (strlen($password) < 6) {
        return array('success' => false, 'message' => 'A senha deve ter pelo menos 6 caracteres');
    }

    // Verificar se usuário já existe
    //todo: Implementar consulta ao banco de dados
    $existing_user = null; // Substituir pela consulta: SELECT id FROM users WHERE email = ?
    
    if ($existing_user) {
        return array('success' => false, 'message' => 'Este email já está cadastrado');
    }

    // Criar hash da senha
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Inserir novo usuário
    //todo: Implementar inserção no banco de dados
    $user_id = null; // Substituir pela inserção: INSERT INTO users (name, email, password) VALUES (?, ?, ?)
    
    if (!$user_id) {
        return array('success' => false, 'message' => 'Erro ao criar usuário');
    }

    // Login automático após registro
    $_SESSION['user_id'] = $user_id;
    $_SESSION['user_email'] = $email;
    $_SESSION['user_name'] = $name;
    $_SESSION['logged_in'] = true;

    return array('success' => true, 'message' => 'Usuário registrado com sucesso');
}

/**
 * Recupera dados do usuário logado
 */
function get_current_user() {
    if (!is_logged_in()) {
        return null;
    }

    //todo: Implementar consulta ao banco de dados
    $user = null; // Substituir pela consulta: SELECT * FROM users WHERE id = ?
    
    return $user;
}

/**
 * Verifica se o usuário tem permissão
 */
function has_permission($permission) {
    if (!is_logged_in()) {
        return false;
    }

    $user = get_current_user();
    
    //todo: Implementar lógica de permissões
    // Substituir pela verificação de permissões do usuário
    
    return true; // Placeholder
}

/**
 * Redireciona usuário não logado
 */
function require_login($redirect_url = 'login.php') {
    if (!is_logged_in()) {
        header('Location: ' . $redirect_url);
        exit();
    }
}

/**
 * Redireciona usuário já logado
 */
function redirect_if_logged_in($redirect_url = 'index.php') {
    if (is_logged_in()) {
        header('Location: ' . $redirect_url);
        exit();
    }
}

/**
 * Altera senha do usuário
 */
function change_password($current_password, $new_password, $confirm_password) {
    if (!is_logged_in()) {
        return array('success' => false, 'message' => 'Usuário não logado');
    }

    if ($new_password !== $confirm_password) {
        return array('success' => false, 'message' => 'As novas senhas não coincidem');
    }

    if (strlen($new_password) < 6) {
        return array('success' => false, 'message' => 'A senha deve ter pelo menos 6 caracteres');
    }

    $user = get_current_user();
    
    //todo: Verificar senha atual
    $current_password_valid = false; // Substituir por: password_verify($current_password, $user['password'])
    
    if (!$current_password_valid) {
        return array('success' => false, 'message' => 'Senha atual incorreta');
    }

    // Atualizar senha
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    
    //todo: Implementar atualização no banco de dados
    $updated = false; // Substituir por: UPDATE users SET password = ? WHERE id = ?
    
    if (!$updated) {
        return array('success' => false, 'message' => 'Erro ao alterar senha');
    }

    return array('success' => true, 'message' => 'Senha alterada com sucesso');
}

/**
 * Gera token CSRF
 */
function generate_csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Verifica token CSRF
 */
function verify_csrf_token($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

?>