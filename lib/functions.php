<?php

function formata_dinheiro(float $valor): string {
    $valor = floatval($valor);

    if (!is_numeric($valor)) {
        throw new InvalidArgumentException('O valor deve ser um número válido');
    }

    return 'R$ ' . number_format($valor, 2, ',', '.');
}