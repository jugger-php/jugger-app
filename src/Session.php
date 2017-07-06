<?php

namespace jugger;

class Session
{
    public function start(array $options = [])
    {
        if ($this->isActive()) {
            return;
        }
        else if (!session_start($options)) {
            $error = error_get_last();
            throw new \Exception($error['message']);
        }
    }

    public function isActive(): bool
    {
        return session_status() === \PHP_SESSION_ACTIVE;
    }

    public function commit()
    {
        session_commit();
    }

    public function abort()
    {
        session_abort();
    }

    public function set(string $name, $value)
    {
        $this->start();
        $_SESSION[$name] =  $value;
    }

    public function get(string $name)
    {
        $this->start();
        return $_SESSION[$name] ?? null;
    }

    public function remove(string $name)
    {
        $this->start();
        $value = $_SESSION[$name] ?? null;
        if ($value) {
            unset($_SESSION[$name]);
        }
        return $value;
    }
}
