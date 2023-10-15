<?php

use easyphp\core\libs\Request;
use easyphp\core\libs\Router;

require_once __DIR__ .'/autoload.php';
require_once __DIR__ .'/easyphp/config/config.php';


$request = new Request();
$router = new Router();
$router->register('web');
$router->resolve($request);
