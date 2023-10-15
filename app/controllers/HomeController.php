<?php

namespace app\controllers;

use app\models\Medicamento;
use easyphp\core\libs\Response;

class HomeController
{
    public function index()
    {
        $medicamento = new Medicamento();
        $data = [
            'page' => 'Index 😘',
            'content' => "Welcome to EasyPHP lite Framework ❤",

        ];
        Response::view('welcome.say_hello', $data);
    }

    public function say(){
        Response::view('welcome.say_hello', [
            'page' => 'Say 😘',
            'content' => "Hello to EasyPHP lite Framework ❤",
        ]);}


    public function group()
    {
        Response::code_response(200)::json([
            "success" => true,
            "data" => [
                [
                    "id" => 1,
                    "name" => "Karim",
                    "lastname" => "Ahmed",
                ],
                [
                    "id" => 2,
                    "name" => "Mohamed",
                    "lastname" => "Ali",
                ],
                [
                    "id" => 3,
                    "name" => "Mahmoud",
                    "lastname" => "Alaa",

                ],
                [
                    "id" => 4,
                    "name" => "Ahmed",
                    "lastname" => "Mahmoud",
                ],
                [
                    "id" => 5,
                    "name" => "Mohamed",
                    "lastname" => "Mahmoud",
                    
                ]
                
            ]
        ]);
    }
}
