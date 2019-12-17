<?php

namespace jugger\repo;

use jugger\db\ConnectionInterface;

abstract class ConnectionRepository extends Repository
{
    protected $db;
    
    public function __construct(ConnectionInterface $db)
    {
        $this->db = $db;
    }
}