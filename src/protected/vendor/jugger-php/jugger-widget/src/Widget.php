<?php

namespace jugger\widget;

use Exception;
use jugger\core\Renderer;

abstract class Widget
{
    protected $assets;
    protected $template;

    abstract protected function runInternal(array $params): string;

    public static function run(string $template, array $params = [], Assets $assets = null): string
    {
        $self = new static();
        $self->assets = $assets;
        $self->template = $template;
        return $self->runInternal($params);
    }

    protected function renderTemplate(array $params = []): string
    {
        if (empty($this->template)) {
            throw new Exception("Template not setted");
        }
        $path = "views/{$this->template}/view";
        return $this->render($path, $params);
    }

    protected function render(string $view, array $params = []): string
    {
        $renderer = new Renderer();
        $renderer->setContext($this);
        return $renderer->render($view, $params);
    }
}