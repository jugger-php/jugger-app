<?php

use jugger\core\Context;
use jugger\core\Lets;
use jugger\widget\Theme;

/**
 * @var Lets    $this
 * @var Context $context
 */

$context->set("theme", new Theme(APP_ROOT_DIR .'/themes'));