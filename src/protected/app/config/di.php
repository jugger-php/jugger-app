<?php

/**
 * @var $di \jugger\core\DependencyContainer
 */

$di->set('errorHandler', '\jugger\core\ErrorHandler');

$di->set('db', function() {
    return new \jugger\db\driver\PdoConnection("mysql:dbname=jugger_local;host=127.0.0.1", "root", "");
});
