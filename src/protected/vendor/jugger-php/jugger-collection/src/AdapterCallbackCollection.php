<?php

namespace jugger\collection;

use Countable;
use Iterator;
use Traversable;

class AdapterCallbackCollection extends IteratorCollection
{
    protected $iterator;
    protected $callback;
    
    public function __construct(Traversable $iterator, callable $callback)
    {
        $this->iterator = $iterator;
        $this->callback = $callback;
    }
    
    public function getIterator(): Iterator
    {
        foreach ($this->iterator as $item) {
            yield call_user_func($this->callback, $item);
        }
    }
    
    public function count(): int
    {
        if ($this->iterator instanceof Countable) {
            return $this->iterator->count();
        }
        return iterator_count($this->iterator);
    }
}