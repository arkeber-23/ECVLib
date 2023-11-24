<?php

declare(strict_types=1);

namespace Kernel\Core\Libs;

class Router
{
    private static string $prefix = '';
    private static array $middlewares = [];
    private static array $routes = [];

    public  function __construct()
    {
        self::register();
    }

    private static  function combineRouteWithPrefix($route): string
    {
        $prefix = rtrim(self::$prefix, '/');
        $route = '/' . ltrim($route, '/');
        return $prefix . $route;
    }

    public static function setPrefix($prefix): self
    {
        self::$prefix = rtrim($prefix, '/');
        return new static();
    }

    public static function addMiddleware($middleware): self
    {
        static::$middlewares[] = $middleware;
        return new static();
    }

    private static function addRoute($method, $uri, $handler): self
    {
        $uri = self::combineRouteWithPrefix($uri);
        static::$routes[$method][$uri] = [
            'handler' => $handler,
            'middlewares' => static::$middlewares
        ];
        static::$middlewares = [];
        return new static();
    }

    public static function get($uri, $handler): self
    {
        return self::addRoute('GET', $uri, $handler);
    }

    public static function post($uri, $handler): self
    {
        return self::addRoute('POST', $uri, $handler);
    }

    public static function put($uri, $handler): self
    {
        return self::addRoute('PUT', $uri, $handler);
    }

    public static function delete($uri, $handler): self
    {
        return self::addRoute('DELETE', $uri, $handler);
    }

    public static function patch($uri, $handler): self
    {
        return self::addRoute('PATCH', $uri, $handler);
    }

    public static function options($uri, $handler): self
    {
        return self::addRoute('OPTIONS', $uri, $handler);
    }
    public static function prefix($prefix, $callback): self
    {
        $groupCallback = function () use ($callback) {
            call_user_func($callback);
        };

        $instance = new self();
        $instance->setPrefix($prefix);
        call_user_func($groupCallback);
        return $instance;
    }

    private function register(): void
    {

        $_dir = __DIR__ . '/../../../src/Routes/';

        $files = glob($_dir . '*.php');
        foreach ($files as $filename) {
            require_once $filename;
        }
    }

    public function resolve(Request $request)
    {

        $callback = null;
        $method = $request->getMethod();
        $url = $request->getUrl();
        if (isset(self::$routes[$method][$url])) {

            $route = self::$routes[$method][$url];
            $callback = $route['handler'];
            $middlewares = $route['middlewares'];

            $middlewares = array_reverse($middlewares);


            $next = function ($request, $response) use ($callback) {
                call_user_func($callback, $request, $response);
            };

            foreach ($middlewares as $middleware) {
                $middlewareInstance = new $middleware();

                $nextMiddleware = function ($request, $response) use ($middlewareInstance, $next) {
                    $middlewareInstance->handle($request, $response, $next);
                };

                $next = $nextMiddleware;
            }

            $next($request, new Response());
        } else {
            Response::httpCode(404);
            echo <<<HTML
                <h1>Error 404 💔</h1>
                <p>Página no encontrada</p>
            HTML;
        }
    }
}
