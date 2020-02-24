<?php

use jugger\assets\AssetsManager;
use jugger\core\Lets;
use jugger\db\driver\MysqliConnection;
use jugger\di\ServiceLocator;

/**
 * @var Lets $this
 */

$appRoot = $this->appRoot;
$services = ServiceLocator::getInstance();

$services->set('jugger\assets\AssetsManager', function() use($appRoot) {
    $publicPath = $appRoot .'../../';
    return new AssetsManager($publicPath, $publicPath .'/assets');
});

$services->set('jugger\db\ConnectionInterface', function() {
    return new MysqliConnection(
        "localhost",
        "root",
        "",
        "jugger_local"
    );
});

$services->set('jugger\core\ErrorHandler', 'jugger\core\ErrorHandler');