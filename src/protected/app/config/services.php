<?php

use jugger\assets\AssetsManager;
use jugger\core\Lets;
use jugger\core\ServiceLocator;

/**
 * @var Lets            $this
 * @var ServiceLocator  $services
 */

$services->set('jugger\assets\AssetsManager', function () {
    return new AssetsManager($this->appRoot);
});