<?php

namespace Kernel\Core\Libs;

class Response
{
    private static array $headers = [];
    public static function httpCode($code)
    {
        http_response_code($code);
        return new self();
    }

    public static function addHeader($name, $value)
    {
        self::$headers [] = "$name: $value";
        foreach (self::$headers as $header) {
            header($header);
        }
        return new self();
    }

    public static function json($data = [], $statusCode = 200, $headers = [])
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        foreach ($headers as $header) {
            list($name, $value) = explode(':', $header, 2);
            self::addHeader(trim($name), trim($value));
        }
        echo json_encode($data);
    }

    public static function view($viewName, $data = [])
    {
        try {
            self::validateViewName($viewName);
            $viewPath = __DIR__ . '/../../../resources/views/' . str_replace('.', '/', $viewName) . '.phtml';

            self::validateViewExists($viewPath);
            
            //lokiEngine
           // $page = new LokiEngine($viewPath, $data);
            //$viewContent = $page->render($data);
            
            ob_start();
            include $viewPath;
            $viewContent = ob_get_clean();
            echo $viewContent;
        } catch (\Exception $e) {
            self::handleError($e);
        }
    }

    public static function redirect($path)
    {
        try {
            self::validateRedirectPath($path);

            $url = BASE_URL . $path;
            if (ob_get_length()) {
                ob_end_clean();
            }
            header("Location: $url");
            exit;
        } catch (\Exception $e) {
            self::handleError($e);
        }
    }

    public static function redirectTo($url)
    {
        try {
            if (ob_get_length()) {
                ob_end_clean();
            }
            header("Location: $url");
            exit;
        } catch (\Exception $e) {
            self::handleError($e);
        }
    }

    private static function validateViewName($viewName)
    {
        if (!preg_match('/^[a-zA-Z0-9_.-]+$/', $viewName)) {
            throw new \InvalidArgumentException("Invalid view name: $viewName");
        }
    }

    private static function validateViewExists($viewPath)
    {
        if (!file_exists($viewPath)) {
            throw new \InvalidArgumentException("View file not found: $viewPath");
        }
    }

    private static function validateRedirectPath($path)
    {
        if (!is_string($path) || empty($path) || !preg_match('/^[a-zA-Z0-9_\-\/]+$/', $path)) {
            throw new \InvalidArgumentException("Invalid redirect path: $path");
        }
    }

    private static function handleError(\Exception $e)
    {
        self::httpCode(400);
        echo $e->getMessage();
        exit;
    }
}
