<?php

namespace jugger\core;

use Throwable;

class ErrorHandler
{
    public function process(Throwable $e)
    {
        echo "<pre>";
        throw $e;
    }
}