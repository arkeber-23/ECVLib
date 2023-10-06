<?php

namespace app\libs;

class Request
{

    protected $contentType;
    protected $method;
    protected $url;
    protected $parameters;
    protected $files;


    public function __construct()
    {
        $this->contentType = $_SERVER['CONTENT_TYPE'] ?? '';
        $this->method = strtoupper($_SERVER['REQUEST_METHOD']);
        $this->url = $_SERVER['PATH_INFO'] ?? '/';
        $this->prepareParams();
        $this->prepareFiles();
    }

    protected function prepareParams()
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
        $this->parameters = filter_var_array($params?? [], FILTER_SANITIZE_SPECIAL_CHARS);
    }

    protected function prepareFiles()
    {
        $this->files = filter_var_array($_FILES ?? [], FILTER_SANITIZE_SPECIAL_CHARS);
    }

	public function getContentType() { 
 		return $this->contentType; 
	} 


	public function getMethod() { 
 		return $this->method; 
	} 

	public function getUrl() { 
 		return $this->url; 
	} 


	public function getParameters() { 
 		return $this->parameters; 
	} 

	public function getFiles() { 
 		return $this->files; 
	} 

	public function setFiles($files) {  
		$this->files = $files; 
	} 
}