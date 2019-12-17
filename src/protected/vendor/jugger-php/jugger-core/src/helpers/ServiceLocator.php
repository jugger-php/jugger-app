<?php

namespace jugger\core\helpers;

use Exception;

class ServiceLocator
{
    protected $configs = [];
    protected $instances = [];
    
    public static function getInstance()
    {
        static $instance = null;
        if ($instance === null) {
            $instance = new static();
        }
        return $instance;
    }

    public function get(string $id)
    {
        if (array_key_exists($id, $this->instances)) {
            return $this->instances[$id];
        }

        $config = $this->configs[$id] ?? null;
        if (is_null($config)) {
            throw new \Exception("Not found container item '{$id}'");
        }
        else {
            return $this->instances[$id] = $this->buildObject($config);
        }
    }
    
    public function has(string $id): bool
    {
        return isset($this->configs[$id]);
    }
    
    public function set(string $id, $config)
    {
        if (is_string($config)) {
            $this->configs[$id] = $config;
        }
        else if (is_callable($config)) {
            $this->configs[$id] = $config;
        }
        else {
            throw new Exception("Invalide config type. Must be 'string' or 'callable'");
        }
    }
    
    protected function buildObject($config)
    {
        if (is_string($config)) {
            return new $config();
        }
        else if (is_callable($config)) {
            return call_user_func($config, $this);
        }
        else {
            return null;
        }
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