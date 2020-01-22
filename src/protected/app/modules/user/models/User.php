<?php

namespace app\modules\user\models;

use jugger\model\field\IntField;
use jugger\model\field\StringField;
use jugger\model\Model;

class User extends Model
{
    public static function getSchema(): array
    {
        return [
            new IntField('id'),
            new StringField('username'),
            new StringField('password'),
        ];
    }
}