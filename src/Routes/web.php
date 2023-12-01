<?php

use App\Models\TestModel;
use Kernel\Core\Libs\Router;
use Kernel\Core\Libs\Response;

Router::get('/', function () {
    Response::view('index');
});




