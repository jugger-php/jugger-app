<?php

namespace jugger\core\response;

class JsonResponse extends HttpResponse
{
    public function __construct()
    {
        $this->setHeader('Content-type', 'application/json');
    }

    public function getContent()
    {
        return json_encode($this->getData());
    }
}