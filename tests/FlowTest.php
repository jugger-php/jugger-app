<?php

use jugger\Application;
use jugger\DependencyContainer;

$di = new DependencyContainer();
$di->setClass();
$di->setSingleton();

$config = [
	'name' => 'Название приложения',
];

$data = null;
$params = func_get_args();

$app = new Application($config, $di);
$app->setRootDir(__DIR__);
$app->run($params, $data);

die();
