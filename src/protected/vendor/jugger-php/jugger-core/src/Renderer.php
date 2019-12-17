<?php

namespace jugger\core;

class Renderer
{
    protected $basePath;
    protected $context;
    
    public function setBasePath(string $path)
    {
        $this->basePath = $path;
    }
    
    public function setContext($context)
    {
        $refClass = new \ReflectionClass($context);
        $this->context = $context;
        if ($this->basePath === null) {
            $this->basePath = dirname($refClass->getFileName());
        }
    }
    
    public function render(string $view): string
    {
        $path = rtrim($this->basePath, '/') ."/{$view}";
        if (!pathinfo($view, \PATHINFO_EXTENSION)) {
            $path .= ".php";
        }
        
        if (file_exists($path)) {
            $context = $this->context;
            try {
                ob_start();
                include $path;
                return ob_get_clean();
            }
            catch (\Throwable $e) {
                while (ob_get_level() > 0) {
                    ob_end_clean();
                }
                throw $e;
            }
        }
        else {
            throw new \Exception("View '{$view}' not found");
        }
    }
}