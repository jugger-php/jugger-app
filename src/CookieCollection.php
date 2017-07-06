<?php

namespace jugger;

class CookieCollection
{
    protected $cookies = [];

    public function add(Cookie $item): void
    {
        $name = $item->name;
        $this->cookies[$name] = $item;
    }

    public function set(string $name, string $value, ?int $expire = null): void
    {
        $cookie = $this->get($name) ?: new Cookie($name);
        $cookie->value = $value;
        if ($expire !== null) {
            $cookie->expire = $expire;
        }
        $this->cookies[$name] = $cookie;
    }

    public function get(string $name): ?Cookie
    {
        return $this->cookies[$name] ?? null;
    }

    public function getValue(string $name): ?string
    {
        $cookie = $this->get($name);
        return $cookie ? $cookie->value : null;
    }

    public function remove(string $name): ?Cookie
    {
        $cookie = $this->get($name);
        if ($cookie) {
            $cookie->expire = time() - 36000000;
            $this->add($cookie);
        }
        return $cookie;
    }

    public function apply(): bool
    {
        foreach ($this->cookies as $cookie) {
            if ($cookie->apply() == false) {
                return false;
            }
        }
        return true;
    }
}
