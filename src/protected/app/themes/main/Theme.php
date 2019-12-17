<?php

namespace app\themes\main;

use jugger\core\Renderer;
use jugger\core\Request;

/**
 * Вынести из приложения
 */
class Theme
{
    public $content;
    public $request;

    public function setRequest(Request $request)
    {
        $this->request = $request;
    }

    public function setContent(string $content)
    {
        $this->content = $content;
    }

    public function render()
    {
        $renderer = new Renderer();
        $renderer->setContext($this);
        return $renderer->render('view');
    }
}