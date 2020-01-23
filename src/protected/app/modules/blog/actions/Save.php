<?php

namespace app\modules\blog\actions;

use app\modules\blog\models\Post;
use Exception;
use app\modules\blog\services\UserService;
use jugger\core\Action;
use jugger\core\Request;
use jugger\core\response\JsonResponse;
use jugger\db\ConnectionInterface;

class Save extends Action
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
        $user = UserService::getUserByTokenRequest($this->request, $this->db);
        if (!$user) {
            throw new Exception("Access denied", 403);
        }

        $post = new Post($this->data);
        $post->id = null;
        $post->user_id = $user->id;
        
        $repo = new PostRepository($this->db);
        $repo->save($post);

        return [
            'id' => $post->id,
        ];
    }
}