<?php

namespace app\modules\main\actions;

use jugger\assets\AssetsManager;
use jugger\core\Action;
use jugger\core\Request;
use jugger\core\web\NotFoundException;

class Page extends Action
{
    protected $assetsManager;

    public function __construct(Request $request, AssetsManager $assetsManager)
    {
        parent::__construct($request);
        $this->assetsManager = $assetsManager;
    }

    public function getAssetsManager(): AssetsManager
    {
        return $this->assetsManager;
    }

    public function runInternal()
    {
        $view  = $this->params['code'] ?? 'main';
        $view  = 'views/'. preg_replace('/[^a-z0-9\-\_]+/i', '', $view);
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