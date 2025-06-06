<?php
spl_autoload_register(function ($namespace) {
    $arquivo = __DIR__ . DIRECTORY_SEPARATOR . str_replace(
        ['App\\', '\\'],
        ['', DIRECTORY_SEPARATOR],
        $namespace
    ) . '.php';

    if (file_exists($arquivo)) {
        require_once($arquivo);
    }
});