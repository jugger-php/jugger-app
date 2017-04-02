<?php

//
// 1. entry
//

$request = new Request();
$request->get = $_GET;
$request->post = $_POST;
$request->session = $_SESSION;
$request->cookie = $_COOKIE;
$request->userAgent = $_SERVER;

// or

$request = (new GlobalsRequestBuilder)->build();

// 
// 2. route
// 

$router = new Router();
$router->routes = [
	// ROUTEs каталога
	'catalog/' => 'pages/catalog/index',
	'catalog/{sectionCode}' => 'pages/catalog/section',
	// строятся по принципу:
	// 	$controller = new \pages\catalog\index\Controller();
	// 	$controller->params = [
	// 		'sectionCode' => '...',
	// 		'elemnentCode' => '...',
	// 	];
	'catalog/{sectionCode}/{elemnentCode}' => 'pages/catalog/element',
	// все запросы начинающиеся с API никак не ретранслируются
	'api/{route:.+}' => 'api/{route:.+}',
	// все запросы уходят на PAGES 
	// 	$route = '/user/profile/view';
	// 	$controller = new \pages\user\profile\view\Controller();
	// 	$validRegexp = ([a-z0-9\/]+)
	'{route:.+}' => 'pages/{route:.+}',
];

// or

$path = __DIR__.'/config/routes.php';
$router = (new ConfigRouterBuilder($path))->build();
// $router->routes = include($path);

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

if (preg_match('/^([a-z0-9\-\_\/]+)\/([a-z0-9\-\_]+)$/i', $route->name, $m)) {
	// replace `/` to `\`
	// and
	// replace `-` to `camelCase`
	$class = $m[0] . $m[1];
}

// or

$action = $route->getClassName();
if (!class_exists($action)) {
	throw new HttpException(404);
}

$action = new $action();
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

$token = $request->get('access_token') ?? $request->cookie['access_token'];
$token = (new TokenBuilder($token))->build();

//
// 6. access manager
//

$accessManager = (new TokenAccessBuilder($token))->build();

if (!$accessManager->checkByAction($action)) {
	throw new HttpException(403);
}

//
// 7. response
//

$response = $action->run();
$response->send();
exit($response->code);
