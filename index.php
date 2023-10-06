<?php
require_once __DIR__ .'/autoload.php';
require_once __DIR__ .'/app/config/config.php';
use app\libs\Request;
use app\libs\Router;

$request = new Request();
$router = new Router();
$router->register('web');
$router->resolve($request);
