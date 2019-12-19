<?php

namespace jugger\widget;

use Exception;
use jugger\core\Action;
use jugger\core\Renderer;
use jugger\core\Request;

class Theme
{
    protected $assets;
    protected $content = "";
    protected $request;
    protected $template;
    protected $basePath;

    public function __construct(string $basePath = null)
    {
        $this->assets = new Assets;
        $this->basePath = $basePath;
    }

    public function setTemplate(string $template)
    {
        $this->template = $template;
    }

    public function setRequest(Request $request)
    {
        $this->request = $request;
    }

    public function getRequest(): ?Request
    {
        return $this->request;
    }

    public function setContent(string $content)
    {
        $this->content = $content;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getAssets(): Assets
    {
        return $this->assets;
    }

    public function render()
    {
        $renderer = new Renderer();
        $renderer->setContext($this);
        $renderer->setBasePath($this->basePath);
        
        if ($this->template) {
            $view = "{$this->template}/view";
        }
        else {
            $view = 'view';
        }
        return $renderer->render($view);
    }

    public function widget(string $className, string $template, array $params): string
    {
        $refClass = new \ReflectionClass($className);
        if (!$refClass->isSubclassOf(Widget::class)) {
            throw new Exception("Arg '{$className}' must be extend ". Widget::class);
        }
        return $className::run($template, $params, $this->assets);
    }

    public function updateResponseAction(Action $action)
    {
        $this->setRequest($action->getRequest());
        $this->setContent($action->getResponse()->getData());
        $action->getResponse()->setData($this->render());
    }
}