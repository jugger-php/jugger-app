<?php

namespace jugger\widget;

use Exception;
use jugger\assets\AssetsManager;
use jugger\core\Action;
use jugger\core\Renderer;
use jugger\core\Request;

class Theme
{
    protected $assetsManager;
    protected $content = "";
    protected $request;
    protected $template;
    protected $basePath;

    public function __construct(string $basePath = null)
    {
        $this->basePath = $basePath;
        $this->assetsManager = new AssetsManager;
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

    public function setAssetsManager(AssetsManager $value)
    {
        $this->assetsManager = $value;
    }

    public function getAssetsManager(): AssetsManager
    {
        return $this->assetsManager;
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
        return $className::run($template, $params, $this->assetsManager);
    }

    public function updateActionResponse(Action $action)
    {
        $this->setRequest($action->getRequest());
        $this->setContent($action->getResponse()->getData());
        $action->getResponse()->setData($this->render());
    }
}