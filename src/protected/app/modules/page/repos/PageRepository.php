<?php

namespace app\modules\page\repos;

use app\modules\page\models\Page;
use jugger\collection\AdapterCallbackCollection;
use jugger\collection\Collection;
use jugger\criteria\Criteria;
use jugger\db\Query;
use jugger\model\Model;
use jugger\repo\ConnectionRepository;

class PageRepository extends ConnectionRepository
{
    public function save(Model $model): bool
    {
        $values = $model->getValues();
        unset($values['id']);

        if ($model->isNewRecord()) {
            $ret = $this->db->insert('page', $values);
        }
        else {
            $ret = $this->db->update('page', $values, [
                'id' => $model->id,
            ]);
        }
        return $ret > 0;
    }
    
    protected function getModelsCollection(?Criteria $criteria): Collection
    {
        $q = (new Query)->from('page');
        if ($criteria) {
            $q->where($criteria);
        }

        $result = $this->db->query($q);
        return new AdapterCallbackCollection($result, function($row) {
            return new Page($row);
        });
    }
}