<?php

namespace jugger;

class Server
{
    protected $session;

    private static $instance;
    public static function getInstance(): Server
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct()
    {
        $this->session = new Session();
    }

    public function get(string $name)
    {
        return $_SERVER[$name] ?? null;
    }

    public function getSession(): Session
    {
        return $this->session;
    }
}
