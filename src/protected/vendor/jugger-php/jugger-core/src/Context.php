<?php

namespace jugger\core;

class Context
{
    protected $params = [];
    
    public function get(string $id)
    {
        return $this->params[$id] ?? null;
    }
    
    public function has(string $id): bool
    {
        return isset($this->configs[$id]);
    }
    
    public function set(string $id, $value)
    {
        $this->params[$id] = $value;
    }
    
    public function __get($id)
    {
        return $this->get($id);
    }
    
    public function __set($id, $value)
    {
        $this->set($id, $value);
    }

    public function __isset($id)
    {
        return $this->has($id);
    }
}