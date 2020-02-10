<?php

namespace app\modules\user\actions;

use Exception;
use app\modules\user\repos\UserRepository;
use jugger\core\Action;
use jugger\core\Request;
use jugger\core\response\JsonResponse;
use jugger\db\ConnectionInterface;

class Auth extends Action
{
    protected $db;

    public function __construct(Request $request, ConnectionInterface $db)
    {
        parent::__construct($request);

        $this->db = $db;
        $this->response = new JsonResponse();
    }

    public function runInternal()
    {
        $username = $this->params['username'] ?? null;
        $password = $this->params['password'] ?? null;
        if (!$username) {
            throw new Exception("Username is required", 400);
        }

        $repo = new UserRepository($this->db);
        $user = $repo->getByUsername($username)->current();
        if (!$user || !$user->checkPassword($password)) {
            throw new Exception("Access denied", 403);
        }

        if (!$user->token) {
            $user->token = $user::generateToken();
            $repo->save($user);
        }

        return [
            'token' => $user->token,
        ];
    }
}