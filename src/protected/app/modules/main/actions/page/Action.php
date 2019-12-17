<?php

namespace app\modules\main\actions\page;

use jugger\core\web\NotFoundException;
use jugger\widget\Theme;

class Action extends \jugger\core\Action
{
    protected $theme;

    public function prepareResponse()
    {
        $theme = $this->context->theme;
        if ($theme instanceof Theme) {
            $theme->setTemplate($this->theme);
            $theme->updateResponseAction($this);
        }
    }

    public function runInternal()
    {
        $view  = $this->params['code'] ?? 'main';
        $view .= '.php';
        if (!file_exists(__DIR__.'/'.$view)) {
            throw new NotFoundException;
        }

        if ($view === 'main.php') {
            $this->theme = 'main-front';
        }
        else {
            $this->theme = 'main';
        }
        return $this->render($view);
    }
}