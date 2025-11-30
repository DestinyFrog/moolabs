<?php
require_once "./lib/auth.php";

if (logout()) {
    header("Location: /");
    exit;
} else {
    echo "Erro ao fazer logout.";
}