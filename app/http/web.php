<?php

use app\controllers\HomeController;
use app\libs\Router;

Router::get('/',[HomeController::class,'index']);

