<?php

namespace app\modules\solutions\models;

use jugger\model\field\FloatField;
use jugger\model\field\IntField;
use jugger\model\field\StringField;
use jugger\model\Model;

/**
 * Готовое решение
 */
class Solution extends Model
{
    public static function getSchema(): array
    {
        return [
            new IntField("id"),
            new StringField("name"),
            new StringField("code"),
            new StringField("desc"),
            new FloatField("price"),
        ];
    }
}