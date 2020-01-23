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
            new StringField('token'),
        ];
    }

    public static function generateToken()
    {
        return bin2hex(random_bytes(10));
    }

    public function checkPassword(string $password)
    {
        return true;
    }
}