<?php

namespace app\modules\page\actions;

use app\modules\page\repos\PageRepository;
use jugger\core\Action;
use jugger\core\Request;
use jugger\core\web\NotFoundException;
use jugger\widget\Theme;

class ViewAction extends Action
{
    protected $repo;

    public function __construct(Request $request, PageRepository $repo)
    {
        parent::__construct($request);

        $this->repo = $repo;
    }

    protected function runInternal()
    {
        $url = $this->params['url'];
        if (!$url) {
            throw new NotFoundException();
        }

        $page = $this->repo->getByUrl($url)->current();
        if (!$page) {
            throw new NotFoundException();
        }
        return $page;
    }

    protected function afterRun()
    {
        $page = $this->response->getData();
        $this->response->setData($page->content);

        $theme = new Theme(APP_ROOT.'/themes/index');
        $theme->setParams([
            'title' => $page->title,
        ]);
        $theme->updateActionResponse($this);
    }
}