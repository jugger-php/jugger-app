<?php

namespace jugger;

class Cookie
{
    public $name;
    public $value;
    public $expire;
    public $path;
    public $domain;
    public $secure;
    public $httponly;

    public function __construct(string $name, string $value, int $expire = 0, string $path = "", string $domain = "", bool $secure = false, bool $httponly = true)
    {
        $this->name = $name;
        $this->value = $value;
        $this->expire = $expire;
        $this->path = $path;
        $this->domain = $domain;
        $this->secure = $secure;
        $this->httponly = $httponly;
    }

    public function apply(): bool
    {
        return setcookie(
            $this->name,
            $this->value,
            $this->expire,
            $this->path,
            $this->domain,
            $this->secure,
            $this->httponly
        );
    }
}
