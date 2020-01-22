<?php

namespace jugger\core\request;

use jugger\core\Request;

class HttpRequest extends Request
{
    protected $headers = [];
    protected $cookies = [];

    public static function build()
    {
        session_start();
        
        $path = trim(explode('?', $_SERVER['REQUEST_URI'])[0], '/');
        $self = new static($path);
        $self->data = $_POST;
        $self->params = $_GET;
        $self->cookies = $_COOKIE;
        $self->headers = self::getRequestHeaders();
        
        return $self;
    }
    
    public function getRequestHeaders()
    {
        if (function_exists('getallheaders')) {
            return getallheaders();
        }
        else {
            $headers = [];
            foreach ($_SERVER as $name => $value) {
                $name = strtolower($name);
                if (substr($name, 0, 5) === 'http_') {
                    $name = str_replace('_', ' ', substr($name, 5));
                    $key = str_replace(' ', '-', ucwords($name));
                    $headers[$key] = $value;
                }
            }
            return $headers;
        }
    }
    
    public function getHeader(string $name)
    {
        return $this->headers[$name] ?? null;
    }
    
    public function getHeaders(): array
    {
        return $this->headers;
    }
    
    public function getCookie(string $name)
    {
        return $this->cookies[$name] ?? null;
    }
    
    public function getCookies(): array
    {
        return $this->cookies;
    }
}