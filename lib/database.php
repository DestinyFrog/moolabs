<?php

function get_conn() {
    $host = "localhost";
    $porta = 3301;
    $usuario = "moolabs";
    $bd = "moolabs";
    $senha = "senha";

    $con = new PDO("mysql:host=$host:$porta;dbname=$bd", $usuario, $senha);
    return $con;
}