<?php

namespace app\libs;

class Router
{
    private static $routes = [];

    public static function get($route, $callback)
    {
        self::$routes['GET'][$route] = $callback;
    }

    public static function post($route, $callback)
    {
        self::$routes['POST'][$route] = $callback;
    }

    public static function put($route, $callback)
    {
        self::$routes['PUT'][$route] = $callback;
    }

    public static function delete($route, $callback)
    {
        self::$routes['DELETE'][$route] = $callback;
    }

    public function register($filename)
    {
        include_once __DIR__ . '/../http/' . $filename . '.php';
    }
    public function resolve(Request $request)
    {
        $callback = null;
        $method = $request->getMethod();
        $url = $request->getUrl();

        if (isset(self::$routes[$method][$url])) {
            $callback = self::$routes[$method][$url];
        }
        if (!is_null($callback)) {
            call_user_func($callback, $request);
        } else {
            http_response_code(404);
            echo <<<HTML
                <h1>Error 404 💔</h1>
                <p>Página no encontrada</p>
            HTML;
        }
    }
}
