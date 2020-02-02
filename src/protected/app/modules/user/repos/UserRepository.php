<?php

namespace app\modules\user\repos;

use jugger\collection\Collection;
use jugger\criteria\Criteria;
use jugger\db\Query;
use jugger\DbCollection\QueryResultCollection;
use jugger\model\Model;
use jugger\repo\ConnectionRepository;

class UserRepository extends ConnectionRepository
{
    public function save(Model $model): bool
    {
        $values = $model->getValues();
        unset($values['id']);
        if ($model->id) {
            $this->db->update('user', $values, [
                'id' => $model->id,
            ]);
        }
        else {
            $this->db->insert('user', $values);
            $model->id = $this->db->getLastInsertId();
        }
        return true;
    }
    
    protected function getModelsCollection(?Criteria $criteria): Collection
    {
        $q = (new Query)->from('user');
        if ($criteria) {
            $q->where($criteria);
        }

        return new QueryResultCollection(
            $this->db->query($q)
        );
    }
}