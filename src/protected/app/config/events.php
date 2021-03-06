<?php

use jugger\core\Action;
use jugger\core\Lets;
use jugger\core\Request;
use jugger\core\Response;

/**
 * @var Lets $this
 */

$this->on(self::EVENT_BEFORE_PARSE_REQUEST, function(Request $request) {
    // echo "EVENT_BEFORE_PARSE_REQUEST<br>";
});

$this->on(self::EVENT_BEFORE_RUN_ACTION, function(Action $action) {
    // echo "EVENT_BEFORE_RUN_ACTION<br>";
});

$this->on(self::EVENT_BEFORE_SEND_RESPONSE, function(Response $response) {
    // echo "EVENT_BEFORE_SEND_RESPONSE<br>";
});