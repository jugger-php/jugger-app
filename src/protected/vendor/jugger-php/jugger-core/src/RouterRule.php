<?php

namespace jugger\core;

class RouterRule
{
    protected $defaultRe = '[a-z0-9\-\_\.]+';
    
    protected $args = [];
    protected $path;
    protected $pattern;
    
    public function __construct(string $pattern)
    {
        $this->pattern = $pattern;
    }

    public function setPath(string $value)
    {
        $this->path = $value;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }
    
    public function getArgs(): array
    {
        return $this->args;
    }
    
    public function equal(string $path): bool
    {
        $re = '/{([a-z0-9]+|[a-z0-9]+:.+?)}/i';
        $pattern = $this->pattern;
        $argNames = [];
        $newPattern = "";
        if (preg_match_all($re, $pattern, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $m) {
                $parts = explode($m[0], $pattern);
                $pattern = $parts[1];
                $newPattern .= preg_quote($parts[0], '/');
                
                $argParts = explode(":", $m[1]);
                $argRe = $argParts[1] ?? $this->defaultRe;
                $newPattern .= "({$argRe})";
                
                $argNames[] = $argParts[0];
            }
        }
        if (empty($newPattern)) {
            $newPattern = preg_quote($this->pattern, '/');
        }

        $pattern = "/^{$newPattern}$/i";
        if (preg_match($pattern, $path, $m)) {
            for ($i=0; $i < count($argNames); $i++) {
                $name = $argNames[$i];
                $value = $m[$i+1];
                $this->args[$name] = $value;
            }
            return true;
        }
        return false;
    }
}