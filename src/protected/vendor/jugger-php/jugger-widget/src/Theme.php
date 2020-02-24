<?php

namespace jugger\widget;

use Exception;
use ReflectionClass;
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
    protected $params = [];

    public function __construct(string $basePath = null, AssetsManager $assetsManager = null)
    {
        $this->basePath = $basePath;
        $this->assetsManager = $assetsManager;
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

    public function setParams(array $params)
    {
        $this->params = $params;
    }

    public function getParams(): array
    {
        return $this->params;
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
            $view = "{$this->template}/template";
        }
        else {
            $view = 'template';
        }
        return $renderer->render($view, $this->getParams());
    }

    public function widget(string $className, string $template, array $params): string
    {
        $refClass = new ReflectionClass($className);
        if (!$refClass->isSubclassOf(Widget::class)) {
            throw new Exception("Arg '{$className}' must be extend ". Widget::class);
        }
        return $className::run($template, $params, $this->assetsManager);
    }

    public function updateActionResponse(Action $action)
    {
        $content = $action->getResponse()->getData() ?: '';
        $this->setRequest($action->getRequest());
        $this->setContent($content);
        $action->getResponse()->setData($this->render());
    }
}