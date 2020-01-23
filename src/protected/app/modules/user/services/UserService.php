<?php

namespace app\modules\blog\services;

use app\modules\user\models\User;
use app\modules\user\repos\UserRepository;
use jugger\core\Request;
use jugger\db\ConnectionInterface;

class UserService
{
    public static function getUserByTokenRequest(Request $request, ConnectionInterface $db): ?User
    {
        $token = $request->getParam('token');
        if (!$token) {
            return null;
        }

        $repo = new UserRepository($db);
        return $repo->getByToken($token);
    }
}