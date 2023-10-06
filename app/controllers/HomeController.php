<?php

namespace app\controllers;

use app\libs\Response;

class HomeController
{
    public function index()
    {
        Response::view('welcome.say_hello',['page' => 'Index 😘']);
    }
}
