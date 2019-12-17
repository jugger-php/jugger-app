<?php

namespace jugger\repo;

use jugger\model\Model;
use jugger\criteria\Criteria;
use jugger\collection\Collection;
use jugger\collection\ArrayCollection;

class StaticRepository extends Repository
{
    protected $models = [];
    
    public function save(Model $model): bool
    {
        $id = count($this->models) + 1;
        $model->id = $id;
        $this->models[$id] = $model;
    }
    
    protected function getModelsCollection(Criteria $criteria): Collection
    {
        return new ArrayCollection($this->models);
    }
}