<?php

namespace jugger\collection;

abstract class IteratorCollection extends Collection
{
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