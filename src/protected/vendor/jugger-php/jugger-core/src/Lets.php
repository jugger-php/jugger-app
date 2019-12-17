<?php

namespace jugger\core;

use jugger\core\Autoloader;
use jugger\core\web\Request as WebRequest;
use jugger\core\web\NotFoundException;
use jugger\core\helpers\SimpleEventManagerTrait;

class Lets
{
    use SimpleEventManagerTrait;
    
    const EVENT_BEFORE_PARSE_REQUEST = 'onBeforeParseRequest';
    const EVENT_BEFORE_RUN_ACTION = 'onBeforeRunAction';
    const EVENT_BEFORE_SEND_RESPONSE = 'onBeforeSendResponse';
    
    protected $appRoot;
    
    protected function __construct(string $appRoot)
    {
        $this->appRoot = $appRoot;
    }
    
    protected function getRules(): array
    {
        $rules = [];
        $path = $this->appRoot .'/config/urls.php';
        if (file_exists($path)) {
            $rules = include $path;
        }
        return is_array($rules) ? $rules : [];
    }
    
    protected function getDi(): DependencyContainer
    {
        $di = new DependencyContainer();
        $path = $this->appRoot .'/config/di.php';
        if (file_exists($path)) {
            include $path;
        }
        return $di;
    }
    
    protected function initEventHandlers()
    {
        $path = $this->appRoot .'/config/events.php';
        if (file_exists($path)) {
            include $path;
        }
    }
    
    public static function go(string $appRoot)
    {
        $autoloader = new Autoloader;
        $autoloader->addNamespace('app\\', $appRoot);
        $autoloader->register();

        $app = new self($appRoot);
        $app->initEventHandlers();
        $di = $app->getDi();
        
        try {
            $rules = $app->getRules();
            $router = new Router($appRoot);
            $router->setRules($rules);
            $request = WebRequest::build();
            
            $app->trigger(self::EVENT_BEFORE_PARSE_REQUEST, [$request]);
            $action = $router->parseRequest($request);
            if ($action === null) {
                throw new NotFoundException();
            }
            $action->setDi($di);
            
            $app->trigger(self::EVENT_BEFORE_RUN_ACTION, [$action]);
            $response = $action->run();
            
            $app->trigger(self::EVENT_BEFORE_SEND_RESPONSE, [$response]);
            $response->send();
        }
        catch (\Throwable $e) {
            if ($di->has('errorHandler')) {
                $di->get('errorHandler')->process($e);
            }
            else {
                throw $e;
            }
        }
    }
}
