<?php

namespace jugger\repo;

use jugger\model\Model;
use jugger\criteria\Criteria;
use jugger\criteria\SimpleCriteriaBuilder;
use jugger\collection\Collection;

abstract class Repository
{
    abstract public function save(Model $model): bool;
    
    abstract protected function getModelsCollection(?Criteria $criteria): Collection;
    
    public function getModel($criteria): ?Model
    {
        return $this->getModels($criteria)
            ->limit(1)
            ->getIterator()
            ->current();
    }
        
    public function getModels($criteria = null): Collection
    {
        if (is_null($criteria)) {
            // pass
        }
        else if (is_array($criteria)) {
            $criteria = SimpleCriteriaBuilder::build($criteria);
        }
        else if (($criteria instanceof Criteria) === false) {
            throw new \InvalidArgumentException("Parametr 'criteria' must be array or ". Criteria::class);
        }
        return $this->getModelsCollection($criteria);
    }
    
    /**
     * Для методов вида 'getById'
     */
    public function __call($method, $args)
    {
        if (stripos($method, 'getby') === 0) {
            $column = substr($method, 5);
            return $this->getModels([
                $column => $args,
            ]);
        }
        else {
            throw new \BadMethodCallException("Method '{$method}' not found");
        }
    }
}