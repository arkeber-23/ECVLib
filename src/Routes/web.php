<?php

use Kernel\Core\Libs\Router;
use Kernel\Core\Libs\Response;

Router::get('/', fn()=>Response::view('index'));




