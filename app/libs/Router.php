<?php

namespace app\libs;

class Router
{
    private static $routes = [];
    private static $prefixStack = [];



    public static function get($route, $callback)
    {
        $route = self::combineRouteWithPrefix($route);
        self::$routes['GET'][$route] = $callback;
    }

    public static function post($route, $callback)
    {
        $route = self::combineRouteWithPrefix($route);
        self::$routes['POST'][$route] = $callback;
    }

    public static function put($route, $callback)
    {
        $route = self::combineRouteWithPrefix($route);
        self::$routes['PUT'][$route] = $callback;
    }

    public static function delete($route, $callback)
    {
        $route = self::combineRouteWithPrefix($route);
        self::$routes['DELETE'][$route] = $callback;
    }


    public static function prefix($prefix, $callback)
    {
        $groupCallback = function () use ($callback) {
            call_user_func($callback);
        };

        self::$prefixStack[] = $prefix;
        call_user_func($groupCallback);
        array_pop(self::$prefixStack);
    }

    private static function combineRouteWithPrefix($route)
    {
        $prefix = implode('/', self::$prefixStack);
        return rtrim($prefix . $route, '/');
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
