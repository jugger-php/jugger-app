<?php

use jugger\core\Lets;
use jugger\core\ServiceLocator;

/**
 * @var Lets            $this
 * @var ServiceLocator  $services
 */

define('APP_THEMES_DIR', APP_ROOT_DIR .'/themes');

$services->set('cache', 'cacheClass');