<?php

session_start();
require_once dirname(__DIR__) . '/lib/database.php';

function is_logged_in() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

function login($email, $password) {
    if (empty($email) || empty($password)) {
        return array('success' => false, 'message' => 'Email e senha são obrigatórios');
    }

    try {
        $pdo = get_conn();
        
        $stmt = $pdo->prepare("SELECT * FROM usuario WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            return ['success' => false, 'message' => 'Usuário não encontrado'];
        }

        if (!password_verify($password, $user['senha'])) {
            return ['success' => false, 'message' => 'Senha incorreta'];
        }

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_name'] = $user['nome'];
        $_SESSION['user_admin'] = (bool)$user['admin'];
        $_SESSION['logged_in'] = true;

        return ['success' => true, 'message' => 'Login realizado com sucesso'];
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Erro no sistema: ' . $e->getMessage()];
    }
}

function logout() {
    $_SESSION = [];

    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    session_destroy();

    return ['success' => true, 'message' => 'Logout realizado com sucesso'];
}

function get_user() {
    if (!is_logged_in()) {
        return null;
    }

    try {
        $pdo = get_conn();
        $stmt = $pdo->prepare("SELECT * FROM vw_usuario WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        return $stmt->fetch(PDO::FETCH_ASSOC);    
    } catch (PDOException $e) {
        return null;
    }
}

function is_admin() {
    if (!is_logged_in()) {
        return false;
    }
    return isset($_SESSION['user_admin']) && $_SESSION['user_admin'] === true;
}

function require_login($redirect_url = 'login.php') {
    if (!is_logged_in()) {
        header('Location: ' . $redirect_url);
        exit();
    }
}

function generate_csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verify_csrf_token($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}