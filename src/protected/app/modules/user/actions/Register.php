<?php

namespace app\modules\user\actions;

use Exception;
use app\modules\user\models\User;
use app\modules\user\repos\UserRepository;
use jugger\core\Action;
use jugger\core\Request;
use jugger\core\response\JsonResponse;
use jugger\db\ConnectionInterface;

class Register extends Action
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
        $passwordRepeat = $this->params['passwordRepeat'] ?? null;
        if (!$username) {
            throw new Exception("Username is required", 400);
        }
        else if (!$password) {
            throw new Exception("Password is required", 400);
        }
        else if ($password !== $passwordRepeat) {
            throw new Exception("Password must be equal repeat", 400);
        }

        $repo = new UserRepository($this->db);
        $existsUser = $repo->getByUsername($username);
        if ($existsUser) {
            throw new Exception("User already register", 400);
        }
        
        $user = new User(compact('username', 'password'));
        $user->token = $user::generateToken();
        if (!$repo->save($user)) {
            throw new Exception("Error");
        }
        return [
            'token' => $user->token,
        ];
    }
}