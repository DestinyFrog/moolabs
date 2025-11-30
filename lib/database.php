<?php

function get_conn() {
    $host = '127.0.0.1';
    $username =  'moolabs';
    $password =  'senha';
    $db = 'moolabs';
    $port =  3306;

    $url = "mysql:host=$host:$port;dbname=$db";
    $pdo = new PDO($url, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $pdo->exec("SET NAMES 'utf8mb4'");
    $pdo->exec("SET CHARACTER SET utf8mb4");
    
    return $pdo;
}