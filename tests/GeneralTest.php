<?php

namespace tests;

use PHPUnit\Framework\TestCase;

class GeneralTest extends TestCase
{
    public function testName()
    {
        $this->initRequestData();

        //
        // 1. entry
        //
        $request = (new GlobalsRequestBuilder)->build(); // class Request
        $request->getPost();
        $request->getQuery()['attribute'];
        $request->getCookies(); // class CookieCollection

        //
        // 1.1 server
        //
        $server = (new GlobalsServerBuilder)->build();
        $server->get('REQUEST_METHOD'); // $_SERVER['REQUEST_METHOD']

        //
        // 1.2 session
        //
        $session = $server->getSession(); // class Session
        $session->set('name', 'value');
        $session->get('name');
        $session->remove('name');

        //
        // 2. route
        //

        $router = new Router();
        $router->routes = [
        	'catalog/' => 'catalog/index',
        	'catalog/{sectionCode}' => 'catalog/section',
        	'catalog/{sectionCode}/{elemnentId:\d+}' => 'catalog/element',
        ];


        // or

        $path = __DIR__.'/config/routes.php';
        $router = (new ConfigRouterBuilder($path))->build();

        //
        // 3. route
        //

        $route = $router->findRoute($request->getUrl());
        $route->name = "";
        $route->params = [];

        if (!$route) {
        	throw new HttpException(404);
        }

        //
        // 4. action
        //

        $actionClass = Action::getClassNameByRoute($route);
        if (!class_exists($actionClass)) {
        	throw new HttpException(404);
        }

        $action = new $actionClass();
        if (false == ($action instanceof Action)) {
        	throw new HttpException(500);
        }

        // or

        $action = (new RouteActionBuilder($route))->build();
        $action->params = [];

        if (!$action) {
        	throw new HttpException(404);
        }

        //
        // 5. token
        //

        $tokenValue = $request->getQuery('access_token') ?? $request->getCookies()->getValue('access_token');
        $token = new AccessToken($tokenValue);
        $token->getUser();
        $token->getRules();

        // or

        $token = (new RequestTokenBuilder($request))->build();

        //
        // 6. access manager
        //

        $accessManager = new AccessManager($token);

        if (!$accessManager->checkByAction($action)) {
        	throw new HttpException(403);
        }

        //
        // 7. response
        //

        $response = $action->run();
        $response->send();
        $response->code;

    }
}
