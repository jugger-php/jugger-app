<?php

namespace jugger\core;

use jugger\core\web\NotFoundException;

class Router
{
    protected $appRoot;
    protected $rules = [];
    
    public function __construct(string $appRoot)
    {
        $this->appRoot = $appRoot;
    }
    
    public function setRules(array $rules)
    {
        $this->rules = $rules;
    }
    
    public function parseRequest(Request $request): ?Action
    {
        $actionClass = null;
        $requestPath = $request->getPath();
        foreach ($this->rules as $rulePattern => $rulePath) {
            $rule = new RouterRule($rulePattern);
            if ($rule->equal($requestPath)) {
                $actionClass = $this->getClassNamespace($rulePath);
                foreach ($rule->getArgs() as $key => $value) {
                    $request->setParam($key, $value);
                }
                break;
            }
        }
        
        if (!$actionClass) {
            return null;
        }
        return new $actionClass($request);
    }
    
    public function getClassNamespace(string $path)
    {
        $parts = explode("/", $path);
        $moduleName = array_shift($parts);
        $actionPath = join("\\", $parts);
        
        Module::registerAutoload($moduleName);
        
        return "app\\modules\\{$moduleName}\\actions\\{$actionPath}\\Action";
    }
}