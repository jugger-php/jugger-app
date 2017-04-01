<?php

//
// 1. entry
//

// $request = new Request();
// $request->get = $_GET;
// $request->post = $_POST;
// $request->session = $_SESSION;
// $request->cookie = $_COOKIE;
// $request->userAgent = $_SERVER;
$request = (new GlobalsRequestBuilder)->build();

// 
// 2. route
// 

$router = new Router();
$router->routes = include(__DIR__.'/config/routes.php');
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

$route = $router->findRoute($request->getUrl());
$route->namespace = "";
$route->params = [];

if (!$route) {
	throw new HttpException(404);
}

//
// 3. api
//

$action = (new RouteActionBuilder($route))->build();
$action->params = [];

if (!$action) {
	throw new HttpException(404);
}

//
// 4. access
// ПРОБЛЕМЫ С СПИСКОМ ПРАВИЛ ДОСТУПА ЕСЛИ НЕ КОНТРОЛЛЕРА
//

$user = (new CookieUserBuilder($request->cookie))->build();
$token = (new CookieTokenBuilder($request->cookie))->build();
$access = (new AccessBuilder($action))->build();
$access->whiteList = require($controller->getAccessRules()); 
//
// каждый контроллер содердит список правил,
// которые пользователь МОЖЕТ производить,
// если какой-то ACTION не указан, значит его нельзя выполнять
// 
// $access->whiteList = [
// 	new ActionRule('/shop/basket/add'),
// 	new CallbackRule(function(){
// 		return false;
// 	})
// ];

//
// 5. response
//

if ($access->checkUser($user) || $access->checkToken($token)) {
	$response = $action->run();
	$response->send();
	exit($response->code);
}
else {
	throw new HttpException(403);
}
