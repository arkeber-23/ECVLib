<?php

namespace app\controllers;

use easyphp\core\libs\Response;

class TestController
{

    public function index()
    {
        Response::view('test');
    }
}
