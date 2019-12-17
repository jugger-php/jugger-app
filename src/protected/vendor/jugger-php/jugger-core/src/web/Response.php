<?php

namespace jugger\core\web;

class Response extends \jugger\core\Response
{
    protected $headers = [];
    protected $cookies = [];
    
    public function setHeader(string $name, string $value)
    {
        $this->headers[$name] = $value;
    }
    
    public function setCookie(string $name, string $value, array $options = [])
    {
        $this->cookies[$name] = [$value, $options];
    }
    
    public function send()
    {
        foreach ($this->headers as $name => $value) {
            header("{$name}: {$value}");
        }
        
        foreach ($this->cookies as $name => $data) {
            list($value, $options) = $data;
            setcookie(
                $name,
                $value,
                $options['expires'] ?? 0,
                $options['path'] ?? "",
                $options['domain'] ?? "",
                $options['secure'] ?? false,
                $options['httponly'] ?? false
            );
        }
        
        echo $this->getContent();
    }
}