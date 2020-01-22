<?php

namespace app\modules\user\repos;

use jugger\collection\ArrayCollection;
use jugger\collection\Collection;
use jugger\criteria\Criteria;
use jugger\model\Model;
use jugger\repo\Repository;

class UserRepository extends Repository
{
    public function save(Model $model): bool
    {
        return true;
    }
    
    protected function getModelsCollection(?Criteria $criteria): Collection
    {
        return new ArrayCollection([]);
    }
}