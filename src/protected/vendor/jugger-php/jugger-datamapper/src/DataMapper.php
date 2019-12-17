<?php

namespace jugger\datamapper;

use jugger\db\QueryResult;
use jugger\db\ConnectionInterface;

abstract class DataMapper
{
    protected $db;
    
    public function __construct(ConnectionInterface $db)
    {
        $this->db = $db;
    }
    
    abstract public static function getTableName(): string;
    
    abstract public static function getSchema(): array;
    
    public static function getPrimaryKey(): string
    {
        return 'id';
    }
    
    public static function getRelations(): array
    {
        return [];
    }
    
    public static function getFieldNames(): array
    {
        $names = [];
        $fields = static::getSchema();
        foreach ($fields as $field) {
            $names[] = $field->getName();
        }
        return $names;
    }
    
    public function update(array $fields, $criteria = null): int
    {
        $names = static::getFieldNames();
        $values = array_filter($fields, function($key) use($names) {
            return in_array($key, $names);
        }, \ARRAY_FILTER_USE_KEY);
        
        return $this->db->update(static::getTableName(), $values, $criteria);
    }
    
    public function updateById(string $id, array $fields): int
    {
        return $this->update($fields, [
            static::getPrimaryKey() => $id,
        ]);
    }
    
    public function insert(array $fields): int
    {
        $names = static::getFieldNames();
        $values = array_filter($fields, function($key) use($names) {
            return in_array($key, $names);
        }, \ARRAY_FILTER_USE_KEY);
        
        return $this->db->insert(static::getTableName(), $values);
    }
    
    public function delete($criteria): int
    {
        return $this->db->delete(static::getTableName(), $criteria);
    }
    
    public function deleteById(string $id): int
    {
        return $this->delete([
            static::getPrimaryKey() => $id,
        ]);
    }
    
    public function query($criteria): QueryResult
    {
        $query = new MapperQuery($this);
        $query->select(static::getFieldNames());
        $query->from(static::getTableName());
        $query->where($criteria);
        return $this->db->query($query);
    }
}