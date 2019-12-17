<?php

namespace app\modules\main\actions\page;

use app\themes\main\Theme;
use jugger\core\web\NotFoundException;

class Action extends \jugger\core\Action
{
    public function prepareResponse()
    {
        $theme = new Theme();
        $theme->setRequest($this->request);
        $theme->setContent($this->response->getData());
        $this->response->setData($theme->render());
    }

    public function runInternal()
    {
        $view  = $this->params['code'] ?? 'main';
        $view .= '.php';
        if (!file_exists(__DIR__.'/'.$view)) {
            throw new NotFoundException;
        }

        return $this->render($view);
    }
}