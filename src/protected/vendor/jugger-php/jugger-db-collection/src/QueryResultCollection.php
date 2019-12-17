<?php

namespace jugger\DbCollection;

use jugger\db\QueryResult;
use jugger\collection\IteratorCollection;

class QueryResultCollection extends IteratorCollection
{
    protected $result;
    
    public function __construct(QueryResult $result)
    {
        $this->result = $result;
    }
    
    public function getIterator(): \Iterator
    {
        return $this->result->getIterator();
    }
    
    public function count(): int
    {
        return $this->result->count();
    }
}