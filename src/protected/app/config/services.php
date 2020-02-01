<?php

use jugger\assets\AssetsManager;
use jugger\core\Lets;
use jugger\core\ServiceLocator;
use jugger\db\driver\MysqliConnection;

/**
 * @var Lets            $this
 * @var ServiceLocator  $services
 */

$appRoot = $this->appRoot;
$publicPath = $appRoot .'../../';

$services->set('jugger\assets\AssetsManager', function() use($publicPath) {
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