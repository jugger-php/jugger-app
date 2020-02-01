<?php

namespace jugger\collection;

abstract class Collection implements \Countable, \IteratorAggregate, \JsonSerializable
{
    protected $limit;
    protected $offset;
        
    /*
     * implements \Iterator
     */
    abstract public function getIterator(): \Iterator;
    
    /*
     * implements \Countable
     */
    abstract public function count(): int;
    
    /*
     * implements \JsonSerializable
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }
    
    /*
     * implements \jugger\collection\Collection
     */
    abstract public function map(callable $callback): Collection;
    abstract public function find(callable $callback);
    abstract public function filter(callable $callback): Collection;
    
    public function toArray(): array
    {
        return iterator_to_array($this->getIterator());
    }
    
    public function limit(int $limit, int $offset = null): Collection
    {
        $this->limit = $limit;
        if ($offset) {
            $this->offset = $offset;
        }
        return $this;
    }
    
    public function offset(int $offset, int $limit = null): Collection
    {
        $this->offset = $offset;
        if ($limit) {
            $this->limit($limit);
        }
        return $this;
    }

    public function current()
    {
        return $this->getIterator()->current();
    }
}