<?php

namespace app\controllers;

use app\libs\Request;
use app\libs\Response;


class HomeController
{
    public function index()
    {
        $data = [
            'page' => 'Index 😘',

        ];
        Response::view('welcome.say_hello', $data);
    }

    public function say(){
        Response::view('welcome.say_hello', [
            'page' => 'Say 😘',
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

                ]
            ]
        ]);
    }
}
