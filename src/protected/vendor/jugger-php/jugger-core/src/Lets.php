<?php

namespace jugger\core;

use Exception;
use jugger\core\Context;
use jugger\core\Autoloader;
use jugger\core\helpers\ServiceLocator;
use jugger\core\web\Request as WebRequest;
use jugger\core\web\NotFoundException;
use jugger\core\helpers\SimpleEventManagerTrait;

class Lets
{
    use SimpleEventManagerTrait;
    
    const EVENT_BEFORE_PARSE_REQUEST = 'onBeforeParseRequest';
    const EVENT_BEFORE_RUN_ACTION = 'onBeforeRunAction';
    const EVENT_BEFORE_SEND_RESPONSE = 'onBeforeSendResponse';
    
    protected function getRules(): array
    {
        $rules = [];
        $path = APP_ROOT_DIR .'/config/urls.php';
        if (file_exists($path)) {
            $rules = include $path;
        }
        return is_array($rules) ? $rules : [];
    }
    
    protected function initEventHandlers()
    {
        $path = APP_ROOT_DIR .'/config/events.php';
        if (file_exists($path)) {
            include $path;
        }
    }

    protected function initServices()
    {
        $services = ServiceLocator::getInstance();
        $path = APP_ROOT_DIR .'/config/services.php';
        if (file_exists($path)) {
            include $path;
        }
    }

    public static function go(string $appRoot)
    {
        define('APP_ROOT_DIR', $appRoot);

        $autoloader = new Autoloader;
        $autoloader->addNamespace('app\\', $appRoot);
        $autoloader->register();

        $app = new self();
        $app->initServices();
        $app->initEventHandlers();

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
            
            $app->trigger(self::EVENT_BEFORE_RUN_ACTION, [$action]);
            $response = $action->run();
            
            $app->trigger(self::EVENT_BEFORE_SEND_RESPONSE, [$response]);
            $response->send();
        }
        catch (\Throwable $e) {
            $services = ServiceLocator::getInstance();
            if ($services->has('errorHandler')) {
                $services->get('errorHandler')->process($e);
            }
            else {
                throw $e;
            }
        }
    }
}
