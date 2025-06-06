<?php
namespace App\Util;

class Functions
{
    static function preparaTexto(string $texto): string
    {
        return trim(htmlspecialchars($texto));
    }
}