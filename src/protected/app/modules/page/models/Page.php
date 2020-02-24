<?php

namespace app\modules\page\models;

use jugger\model\field\IntField;
use jugger\model\field\StringField;
use jugger\model\Model;

class Page extends Model
{
    public static function getSchema(): array
    {
        return [
            new IntField('id'),
            new StringField('url'),
            new StringField('title'),
            new StringField('content'),
        ];
    }
}