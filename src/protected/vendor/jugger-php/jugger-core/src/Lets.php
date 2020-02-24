<?php

namespace jugger\core;

use jugger\core\Autoloader;
use jugger\di\ServiceLocator;
use jugger\core\web\NotFoundException;
use jugger\core\helpers\SimpleEventManagerTrait;
use jugger\core\request\HttpRequest;

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
    
    protected function initEvents()
    {
        $path = $this->appRoot .'/config/events.php';
        if (file_exists($path)) {
            include $path;
        }
    }

    protected function initServices()
    {
        $path = $this->appRoot .'/config/services.php';
        if (file_exists($path)) {
            include $path;
        }
    }

    protected function createActionClassByRoute(string $path, Request $request): Action
    {
        $parts = explode("/", $path);
        $moduleName = array_shift($parts);
        $actionName = ucfirst(array_pop($parts));

        $actionClass = null;
        $subNamespace = join("\\", $parts);
        if ($subNamespace) {
            $actionClass = "app\\modules\\{$moduleName}\\actions\\{$subNamespace}\\{$actionName}Action";
        }
        else {
            $actionClass = "app\\modules\\{$moduleName}\\actions\\{$actionName}Action";
        }
        return ServiceLocator::getInstance()->createObject($actionClass, [ $request ]);
    }

    protected function initAutoloader()
    {
        $autoloader = new Autoloader;
        $autoloader->addNamespace('app\\', $this->appRoot);
        $autoloader->register();
    }

    public static function go(string $appRoot)
    {
        $app = new self($appRoot);
        $app->initAutoloader();
        $app->initServices();
        $app->initEvents();

        try {
            $rules = $app->getRules();
            $router = new Router();
            $router->setRules($rules);
            $request = HttpRequest::build();
            
            $app->trigger(self::EVENT_BEFORE_PARSE_REQUEST, [$request]);
            $actionPath = $router->parseRequest($request);
            if (!$actionPath) {
                throw new NotFoundException();
            }
            
            $action = $app->createActionClassByRoute($actionPath, $request);
            $app->trigger(self::EVENT_BEFORE_RUN_ACTION, [$action]);
            $response = $action->run();
            
            $app->trigger(self::EVENT_BEFORE_SEND_RESPONSE, [$response]);
            $response->send();
        }
        catch (\Throwable $e) {
            $services = ServiceLocator::getInstance();
            if ($services->has('jugger\core\ErrorHandler')) {
                $services->get('jugger\core\ErrorHandler')->process($e);
            }
            else {
                throw $e;
            }
        }
    }
}
