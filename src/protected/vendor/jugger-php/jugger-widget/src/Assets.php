<?php

namespace jugger\widget;

use Exception;

class Assets
{
    protected $assets = [];

    public function add(string $type, $value)
    {
        if (is_string($value)) {
            $name = md5($value);
        }
        else if (is_array($value)) {
            $name = $value['name'] ?? md5(serialize($value));
        }
        else {
            throw new Exception("Arg 'value' must be string or array");
        }

        $type = strtolower($type);
        $assets = $this->assets[$type] ?? [];
        $assets[$name] = $value;
        $this->assets[$type] = $assets;
    }

    public function addJs($value)
    {
        $this->add('js', $value);
    }

    public function addCss($value)
    {
        $this->add('css', $value);
    }
}