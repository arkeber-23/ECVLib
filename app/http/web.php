<?php

use app\controllers\HomeController;
use app\libs\Router;

Router::get('/home',[HomeController::class,'index']);

Router::prefix('/api',function(){
    Router::get('/',[HomeController::class,'group']);
    Router::get('/say',[HomeController::class,'say']);
});

