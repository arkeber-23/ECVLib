<?php

use Kernel\config\Config;
use Kernel\Core\Libs\Request;
use Kernel\Core\Libs\Router;

require_once __DIR__ . '/vendor/autoload.php';

try {
    new Config('America/Guayaquil', __DIR__ . '/.env');
    $router = new Router();
    $router->resolve(new Request());
} catch (\Exception $e) {
    echo $e->getMessage(); 
}
