<?php

namespace jugger\collection;

class ArrayCollection extends Collection
{
    protected $data;
    
    public function __construct(array $data)
    {
        $this->data = $data;
    }
    
    public function getIterator(): \Iterator
    {
        $limit = $this->limit ?: null;
        $offset = $this->offset ?: 0;
        return new \ArrayIterator(array_slice($this->data, $offset, $limit));
    }
    
    public function count(): int
    {
        return iterator_count($this->getIterator());
    }
    
    public function map(callable $callback): Collection
    {
        $new = [];
        foreach ($this->getIterator() as $key => $value) {
            $new[$key] = call_user_func($callback, $value, $key);
        }
        return new ArrayCollection($new);
    }
    
    public function filter(callable $callback): Collection
    {
        $new = [];
        foreach ($this->getIterator() as $key => $value) {
            $ret = call_user_func($callback, $value, $key);
            if ($ret === true) {
                $new[$key] = $value;
            }
        }
        return new ArrayCollection($new);
    }
    
    public function find(callable $callback)
    {
        foreach ($this->getIterator() as $key => $value) {
            $ret = call_user_func($callback, $value, $key);
            if ($ret === true) {
                return $value;
            }
        }
        return null;
    }
}