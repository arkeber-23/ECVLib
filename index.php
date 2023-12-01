<?php

use Kernel\config\Config;
use Kernel\Core\Libs\Request;
use Kernel\Core\Libs\Router;
use Kernel\Core\Logger\Errors;

require_once __DIR__ . '/vendor/autoload.php';

try {
    new Config('America/Guayaquil', __DIR__ . '/.env');
    $router = new Router();
    $router->resolve(new Request());
} catch (\Exception $e) {
    $error = new Errors('INDEX', 'errors.log', 'debug');
    $msg =  $e->getMessage() . " time: " . date('Y-m-d H:i:s') . "\n" . PHP_EOL;
    $error->critical($msg);
}
