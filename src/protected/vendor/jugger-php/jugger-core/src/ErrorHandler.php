<?php

namespace jugger\core;

class ErrorHandler
{
    public function process(\Throwable $e)
    {
        throw $e;
    }
}