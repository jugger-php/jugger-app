<?php

namespace jugger\core\helpers;

trait SimpleEventManagerTrait
{
    protected $eventHandlers = [];
    
    public function on(string $name, callable $handler)
    {
        if (!isset($this->eventHandlers[$name])) {
            $this->eventHandlers[$name] = [];
        }
        $this->eventHandlers[$name][] = $handler;
    }
    
    protected function trigger(string $name, array $params)
    {
        $handlers = $this->eventHandlers[$name] ?? [];
        foreach ($handlers as $handler) {
            call_user_func($handler, ...$params);
        }
    }
}