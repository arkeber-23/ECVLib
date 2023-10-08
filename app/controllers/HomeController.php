<?php

namespace app\controllers;

use app\libs\Response;
use app\models\Medicamento;

class HomeController
{
    public function index()
    {
        $data = [
            'page' => 'Index 😘',
        ];
        Response::view('welcome.say_hello', $data);
    }
}
