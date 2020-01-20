<?php

namespace jugger\assets;

class Asset
{
    protected $type;
    protected $name;
    protected $value;
    protected $params = [];

    public function __construct($value, array $params = [])
    {
        $this->value = $value;
        $this->params = $params;
    }

    public function setType(string $type)
    {
        $this->type = $type;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getParams(): array
    {
        return $this->params;
    }
}