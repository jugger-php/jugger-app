<?php

namespace jugger\core\web;

use Exception;

class NotFoundException extends Exception
{
    public function __construct(string $message = "Not found")
    {
        parent::__construct($message, 404);
    }
}