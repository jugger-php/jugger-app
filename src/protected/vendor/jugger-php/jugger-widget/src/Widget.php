<?php

namespace jugger\widget;

use Exception;
use jugger\core\Renderer;

abstract class Widget
{
    protected $template;

    abstract protected function runInternal(array $params): string;

    public static function run(string $template, array $params = []): string
    {
        $self = new static();
        $self->template = $template;
        return $self->runInternal($params);
    }

    protected function renderTemplate(): string
    {
        if (empty($this->template)) {
            throw new Exception("Template not setted");
        }
        $path = "views/{$this->template}/view";
        return $this->render($path);
    }

    protected function render(string $view): string
    {
        $renderer = new Renderer();
        $renderer->setContext($this);
        return $renderer->render($view);
    }
}