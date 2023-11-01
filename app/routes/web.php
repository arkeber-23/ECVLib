<?php

use app\controllers\TestController;
use easyphp\core\libs\Response;
use easyphp\core\libs\Router;

Router::get('/', function () {
    echo 'Hello World!';
});

Router::get('/home', [new TestController, 'index']);

Router::prefix('/api', function () {
    Router::get('/', function () {
        Response::http_code(200)::json(['hello' => 'world']);
    });
});
