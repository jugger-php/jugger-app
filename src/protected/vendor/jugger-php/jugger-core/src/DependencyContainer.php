<?php

namespace jugger\core;

/**
 * Контейнер зависимостей/сервисов
 */
class DependencyContainer implements \ArrayAccess
{
    protected $items = [];
    protected $instances = [];
    
    public function get(string $id)
    {
        $config = $this->items[$id] ?? null;
        if (is_null($config)) {
            throw new \Exception("Not found container item '{$id}'");
        }
        else if (array_key_exists($id, $this->instances)) {
            return $this->instances[$id];
        }
        else {
            return $this->instances[$id] = $this->buildObject($config);
        }
    }
    
    public function has(string $id): bool
    {
        return isset($this->items[$id]);
    }
    
    /**
     * Добавление нового экземпляра
     * @param string            $id     идентификатор класса
     * @param string|callback   $config может принимать либо путь до класса, либо колбэк в параметрах которого передается сам контейнер
     */
    public function set(string $id, $config)
    {
        if (is_string($config)) {
            $this->items[$id] = $config;
        }
        else if (is_callable($config)) {
            $this->items[$id] = $config;
        }
        else {
            throw new \Exception("Invalide config type. Must be 'string' or 'callable'");
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
    
    /*
     * ArrayAccess|ObjectAccess implement
     */
    
    public function __isset($id)
    {
        return $this->offsetExists($id);
    }
    
    public function offsetExists($offset)
    {
        return $this->has($offset);
    }
    
    public function __get($id)
    {
        return $this->offsetGet($id);
    }
    
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }
    
    public function __set($id, $value)
    {
        $this->offsetSet($id, $value);
    }
    
    public function offsetSet($offset, $value)
    {
        $this->set($offset, $value);
    }
    
    public function __unset($id)
    {
        $this->offsetUnset($id);
    }
    
    public function offsetUnset($offset)
    {
        throw new \Exception("Not implement 'unset' method");
    }
}