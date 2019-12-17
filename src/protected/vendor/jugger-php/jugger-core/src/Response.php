<?php

namespace jugger\core;

class Response
{
    protected $data;
    
    public function setData($data)
    {
        $this->data = $data;
    }
    
    public function getData()
    {
        return $this->data;
    }
    
    public function getContent()
    {
        return $this->getData();
    }
    
    public function send()
    {
        throw new \Exception("Not sendable response");
    }
}