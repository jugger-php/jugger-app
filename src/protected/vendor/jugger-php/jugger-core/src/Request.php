<?php

namespace jugger\core;

class Request
{
    protected $path;
    protected $data;
    protected $params;
    
    public function __construct(string $path)
    {
        $this->path = $path;
    }
    
    public function getPath()
    {
        return $this->path;
    }
    
    public function setParam(string $name, $value)
    {
        $this->params[$name] = $value;
    }
    
    public function getParam(string $name)
    {
        return $this->params[$name] ?? null;
    }
    
    public function getParams(): array
    {
        return $this->params;
    }
    
    public function setData(string $name, $value)
    {
        $this->data[$name] = $value;
    }
    
    public function getData(string $name)
    {
        return $this->data[$name] ?? null;
    }
    
    public function getDatas(): array
    {
        return $this->data;
    }
}