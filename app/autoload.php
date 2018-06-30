<?php


function pathFromFqn(string $fqn): string {
    return ROOT . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR
        . 'objects' . DIRECTORY_SEPARATOR
        . str_replace(
            '\\', DIRECTORY_SEPARATOR, $fqn
        ) . '.php';
}

function isFileNotAccessible(string $path): bool {
    return !is_file($path)
        ||
        !is_readable($path);
}

spl_autoload_register(
    function (string $fqn) {
        $path = pathFromFqn($fqn);

        if (isFileNotAccessible($path)) {
            return ;
        }

        (function (string $path) {
            require_once $path;
        })($path);
    }
);