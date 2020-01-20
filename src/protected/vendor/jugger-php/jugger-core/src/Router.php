<?php

namespace jugger\core;

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
    
    public function parseRequest(Request $request): ?string
    {
        $requestPath = $request->getPath();
        foreach ($this->rules as $rulePattern => $rulePath) {
            $rule = new RouterRule($rulePattern);
            if ($rule->equal($requestPath)) {
                foreach ($rule->getArgs() as $key => $value) {
                    $request->setParam($key, $value);
                }
                return $rulePath;
            }
        }
        return null;
    }
}