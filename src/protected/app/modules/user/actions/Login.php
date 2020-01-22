<?php

namespace app\modules\user\actions;

use app\modules\user\repos\UserRepository;
use Exception;
use jugger\core\Action;
use jugger\core\response\JsonResponse;

class Login extends Action
{
    public function init()
    {
        $this->response = new JsonResponse();
    }

    public function runInternal()
    {
        $username = $this->data['username'] ?? null;
        $password = $this->data['password'] ?? null;

        if (!$username) {
            throw new Exception("Username is required", 400);
        }

        $repo = new UserRepository();
        $user = $repo->getByUsername($username);
        if (!$user || !$user->checkPassword($password)) {
            throw new Exception("Access denied", 403);
        }

        $token = $user->getToken() ?: $user->generateNewToken();
        return [
            'token' => $token,
        ];
    }
}