<?php

use easyphp\core\libs\Router;



Router::get('/home',['app\controllers\HomeController','index']);

 Router::prefix('/api',function(){
    Router::get('/',['app\controllers\HomeController','group']);
    Router::get('/say',['app\controllers\HomeController','say']);
});
 
