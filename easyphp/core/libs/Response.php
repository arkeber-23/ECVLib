<?php

namespace easyphp\core\libs;

class Response
{

    public static function code_response($code)
    {
        http_response_code($code);
        return new self();
    }

    public static function json($data = [])
    {
        header('Content-Type:application/json');
        echo json_encode($data);
    }


    

    public static function view($viewName, $data = [])
    {
       
        if (!preg_match('/^[a-zA-Z0-9_.-]+$/', $viewName)) {
            self::code_response(400);
            echo "Nombre de vista no válido";
            return;
        }
        $viewPath = __DIR__ . '/../../../resources/views/' . str_replace('.', '/', $viewName) . '.phtml';

        if (!file_exists($viewPath)) {
            self::code_response(400);
            echo "La vista <strong>$viewName</strong> no existe";
            return;
        }
        extract($data);
        ob_start();
        include $viewPath;
        $viewContent = ob_get_clean();
        echo $viewContent;
    }
    

    public static function redirect($path)
    {
        header("Location:$path");
    }
}
