<?php

use App\Middlewares\TestMiddleware;
use Kernel\Core\Libs\Router;
use Kernel\Core\Libs\Request;
use Kernel\Core\Libs\Response;

Router::get('/', function () {
    Response::view('index');
});


Router::post('/hola', function (Request $request) {

    $request->validate(
        [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8',
            'number' => 'required|numeric|min:2|max:10',
        ],[
            'name.required' => 'El campo nombre es requerido',
            'email.required' => 'El campo email es requerido',
            'email.email' => 'El campo email no es valido',
            'password.required' => 'El campo password es requerido',
            'password.min' => 'El campo password debe tener al menos 8 caracteres',
            'number.required' => 'El campo number es requerido',
            'number.numeric' => 'El campo number debe ser numerico',
            'number.min' => 'El campo number debe ser mayor o igual a 2',
            'number.max' => 'El campo number debe ser menor o igual a 10',
        ]
    );
});



