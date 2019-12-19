<?php

namespace app\modules\main\actions\page;

use jugger\core\web\NotFoundException;
use jugger\widget\Theme;

class Action extends \jugger\core\Action
{
    protected $themeTemplate;

    public function prepareResponse()
    {
        $theme = new Theme(APP_THEMES_DIR);
        $theme->setTemplate($this->themeTemplate);
        $theme->updateResponseAction($this);
    }

    public function runInternal()
    {
        $view  = $this->params['code'] ?? 'main';
        $view .= '.php';
        if (!file_exists(__DIR__.'/'.$view)) {
            throw new NotFoundException;
        }

        if ($view === 'main.php') {
            $this->themeTemplate = 'main-front';
        }
        else {
            $this->themeTemplate = 'main';
        }
        return $this->render($view);
    }
}