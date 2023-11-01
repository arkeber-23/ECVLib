<?php

use easyphp\core\libs\Response;

function loadClass($class)
{
    $class = str_replace('\\', '/', $class) . '.php';
    $file = __DIR__ . '/' . $class;
    if (!file_exists($file)) {
        Response::http_code(404);
        echo $class.' not found';
        exit;
    }

    require_once $file;
}

spl_autoload_register('loadClass');





