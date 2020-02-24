<?php

namespace app\modules\solutions\repos;

use jugger\collection\ArrayCollection;
use jugger\collection\Collection;
use jugger\criteria\Criteria;
use jugger\repo\ConnectionRepository;

class SolutionRepository extends ConnectionRepository
{
    protected function getModelsCollection(?Criteria $criteria): Collection
    {
        return new ArrayCollection([]);
    }
}