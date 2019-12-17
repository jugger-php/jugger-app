<?php

namespace jugger\datamapper;

use jugger\db\Query;

/**
 * На лету подменяет Relations
 */
class MapperQuery extends Query
{
    protected $mapper;
    
    public function __construct(DataMapper $mapper)
    {
        $this->mapper = $mapper;
    }
}