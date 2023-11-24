<?php

namespace Kernel\Core\Libs;

class Request
{

    use ValidateForm;
    protected string $contentType;
    protected string $method;
    protected string $url;
    protected array $parameters;
    protected array $files;
    protected array $headers;
    public function __construct()
    {
        $this->contentType = $_SERVER['CONTENT_TYPE'] ?? '';
        $this->method = strtoupper($_SERVER['REQUEST_METHOD']);
        $this->url = $_SERVER['PATH_INFO'] ?? '/';
        $this->prepareParams();
        $this->prepareFiles();
        $this->headers = getallheaders();
    }

    protected function prepareParams(): void
    {
        if (strtoupper($_SERVER['REQUEST_METHOD']) == 'GET') {
            $params = $_REQUEST;
        } else {
            if (strtoupper($_SERVER['CONTENT_TYPE']) == 'APPLICATION/JSON') {
                $params = json_decode(file_get_contents('php://input'), true);
            } else {
                $params = $_POST;
            }
        }
        $this->parameters = filter_var_array($params ?? [], FILTER_SANITIZE_SPECIAL_CHARS);
    }

    protected function prepareFiles(): void
    {
        $this->files = filter_var_array($_FILES ?? [], FILTER_SANITIZE_SPECIAL_CHARS);
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function inputHeader($name): string
    {
        return $this->headers[$name] ?? 'Header not found';
    }

    public function getContentType(): string
    {
        return $this->contentType;
    }


    public function getMethod(): string
    {
        return $this->method;
    }

    public function getUrl(): string
    {
        return $this->url;
    }


    public function getParameters(): array
    {
        return $this->parameters;
    }

    public function input($name): string
    {
        return $this->parameters[$name] ?? '';
    }

    public function getFiles(): array
    {
        return $this->files;
    }

    public function inputFile($files): void
    {
        $this->files = $files;
    }
}
