<?php

/**
 * Clase Router
 *
 * Esta clase proporciona una funcionalidad de enrutamiento simple para dirigir las solicitudes HTTP
 * a controladores y acciones específicas.
 */

namespace easyphp\core\libs;

class Router
{
    /**
     * @var array $routes
     * Almacena las rutas definidas por el usuario y sus controladores correspondientes.
     */
    private static $routes = [];

    /**
     * @var array $prefixStack
     * Almacena una pila de prefijos de ruta utilizados en las rutas definidas.
     */
    private static $prefixStack = [];

    public function __construct()
    {
        $this->register();
    }


    /**
     * Define una ruta GET.
     *
     * @param string $route
     * @param array $callback
     */
    public static function get($route, $callback)
    {
        $route = self::combineRouteWithPrefix($route);
        self::$routes['GET'][$route] = $callback;
    }

    /**
     * Define una ruta POST.
     *
     * @param string $route
     * @param array $callback
     */
    public static function post($route, $callback)
    {
        $route = self::combineRouteWithPrefix($route);
        self::$routes['POST'][$route] = $callback;
    }

    /**
     * Define una ruta PUT.
     *
     * @param string $route
     * @param array $callback
     */
    public static function put($route, $callback)
    {
        $route = self::combineRouteWithPrefix($route);
        self::$routes['PUT'][$route] = $callback;
    }

    /**
     * Define una ruta DELETE.
     *
     * @param string $route
     * @param array $callback
     */
    public static function delete($route, $callback)
    {
        $route = self::combineRouteWithPrefix($route);
        self::$routes['DELETE'][$route] = $callback;
    }

    /**
     * Define un grupo de rutas con un prefijo común.
     *
     * @param string $prefix
     * @param callable $callback
     */
    public static function prefix($prefix, $callback)
    {
        $groupCallback = function () use ($callback) {
            call_user_func($callback);
        };

        self::$prefixStack[] = $prefix;
        call_user_func($groupCallback);
        array_pop(self::$prefixStack);
    }

    /**
     * Combina una ruta con los prefijos definidos en la pila.
     *
     * @param string $route
     * @return string
     */
    private static function combineRouteWithPrefix($route)
    {
        $prefix = implode('/', self::$prefixStack);
        return rtrim($prefix . $route, '/');
    }

    /**
     * Registra rutas desde un archivo.
     *
     * @param string $filename
     */
    private function register()
    {
        $_dir = __DIR__ . '/../../../app/routes/';
        $files = glob($_dir . '*');
        foreach ($files as $filename) {
            require_once $filename;
        }
    }

    /**
     * Resuelve una solicitud y llama al controlador y la acción correspondientes.
     *
     * @param Request $request
     */
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
            Response::http_code(404);
            echo <<<HTML
                <h1>Error 404 💔</h1>
                <p>Página no encontrada</p>
            HTML;
        }
    }
}
