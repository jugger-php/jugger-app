<?php

namespace app\modules\user\repos;

use app\modules\user\models\User;
use jugger\collection\AdapterCallbackCollection;
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

        $result = $this->db->query($q);
        $collection = new QueryResultCollection($result);
        return new AdapterCallbackCollection($collection, function($item) {
            return new User($item);
        });
    }
}