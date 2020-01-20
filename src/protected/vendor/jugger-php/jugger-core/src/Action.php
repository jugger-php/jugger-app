<?php

namespace jugger\core;

use jugger\core\web\Response as WebResponse;

abstract class Action
{
    protected $request;
    protected $response;
    
    abstract protected function runInternal();
    
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->response = new WebResponse();
        $this->init();
    }

    protected function init()
    {
        
    }

    public function getRequest(): Request
    {
        return $this->request;
    }

    public function getResponse(): Response
    {
        return $this->response;
    }
    
    public function run(): Response
    {
        $this->beforeRun();
        if ($data = $this->runInternal()) {
            $this->response->setData($data);
        }
        $this->afterRun();
        return $this->response;
    }

    protected function beforeRun()
    {
        
    }

    protected function afterRun()
    {
        
    }
    
    protected function render(string $name)
    {
        $renderer = new Renderer();
        $renderer->setContext($this);
        return $renderer->render($name);
    }
    
    public function __get($name)
    {
        if ($name === 'params') {
            return $this->request->getParams();
        }
        else if ($name === 'data') {
            return $this->request->getDatas();
        }
        else {
            throw new \ErrorException("Property '{$name}' not found");
        }
    }
}

// trait ActionCache
// {
//     protected function getCache()
//     {
//         return $di->get('cache');
//     }
// 
//     protected function getCacheKey(): string
//     {
//         return serialize($this->request->getParams());
//     }
// 
//     protected function getCacheDuration(): int
//     {
//         return 0;
//     }
// 
//     protected function getCacheDependency()
//     {
//         return null;
//     }
// 
//     public function run(): Response
//     {
//         $key = $this->getCacheKey();
//         $cache = $this->getCache();
//         if ($cache->has($key)) {
//             $response = $cache->get($key);
//         }
//         else {
//             $response = parent::run();
//             $cache->set($key, $response, $this->getCacheDuration(), $this->getCacheDependency());
//         }
//         return $response;
//     }
// }
