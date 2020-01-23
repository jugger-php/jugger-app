<?php

namespace app\modules\blog\models;

use jugger\model\field\IntField;
use jugger\model\field\StringField;
use jugger\model\Model;

class Post extends Model
{
    public static function getSchema(): array
    {
        return [
            new IntField('id'),
            new IntField('user_id'),
            new StringField('title'),
            new StringField('content'),
        ];
    }
}