<?php

namespace jugger;

class Request
{
    protected $args; // GET
    protected $data; // POST
    protected $cookies;

    public function __construct(array $args, array $data, CookieCollection $cookies)
    {
        $this->args = $args;
        $this->data = $data;
        $this->cookies = $cookies;
    }

    public function getArgs(): array
    {
        return $this->args;
    }

    public function getArgValue(string $name)
    {
        return $this->getArgsValue($name);
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function getDataValue(string $name)
    {
        return $this->data[$name] ?? null;
    }

    public function getCookies(): CookieCollection
    {
        return $this->cookies;
    }

    public function getCookieValue(string $name): Cookie
    {
        return $this->getCookies()->getValue($name);
    }
}
