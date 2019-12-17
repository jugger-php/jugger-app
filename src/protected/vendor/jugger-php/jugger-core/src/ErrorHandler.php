<?php

namespace jugger\core;

class ErrorHandler
{
    public function process(\Throwable $e)
    {
        echo "<pre>";
        throw $e;
    }
}