<?php

namespace app\modules\page\actions;

use jugger\core\Action;
use jugger\widget\Theme;

class IndexAction extends Action
{
    protected function runInternal()
    {
        return $this->render('index');
    }

    protected function afterRun()
    {
        $theme = new Theme(APP_ROOT.'/themes/index');
        $theme->updateActionResponse($this);
    }
}