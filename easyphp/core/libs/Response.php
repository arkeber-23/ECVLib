<?php

namespace easyphp\core\libs;

class Response
{

    public static function http_code($code)
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
            self::http_code(400);
            echo "Nombre de vista no válido";
            return;
        }
        $viewPath = __DIR__ . '/../../../resources/views/' . str_replace('.', '/', $viewName) . '.phtml';

        if (!file_exists($viewPath)) {
            self::http_code(400);
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
        if (is_string($path) && !empty($path) && preg_match('/^[a-zA-Z0-9_\-\/]+$/', $path)) {
            $url = BASE_URL  . $path;
            if (ob_get_length()) {
                ob_end_clean();
            }
            header("Location: $url");
            exit;
        } else {
            echo "redirect failed";
            exit;
        }
    }
}
