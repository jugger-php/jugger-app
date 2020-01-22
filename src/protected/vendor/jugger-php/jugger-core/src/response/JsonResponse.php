<?php

namespace jugger\core\response;

use jugger\core\Response;

class JsonResponse extends Response
{
    public function getContent()
    {
        return json_encode($this->getData());
    }

    public function send()
    {
        echo $this->getContent();
    }
}