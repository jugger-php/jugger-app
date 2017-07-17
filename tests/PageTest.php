<?php

/*
 * просмотр поста, нужно:
 * 1. вывести страницу
 * 2. обновить счетчик просмотров
 * 3. оповестить автора о новом просмотре (херь но для теста)
 */

$postId = (int) $request->get('pid');

// AR

$post = Post::findOne($postId);
$post->views++;
$post->save();

$author = Author::findOne($post->id_author);
$message = new Message([
    'id_author' => $author->id,
    'message' => 'Новый просмотр',
]);
$message->save();


// REPOSITORY

$post = PostRepo::getById($postId);
$post->views++;
PostRepo::save($post);

$message = new Message([
    'id_author' => $post->author->id, // в отличии от AR в модели Repo уже все свойства проставлены (не лениво), но опять таки это решаемо ленивой загрузкой
    'message' => 'Новый просмотр',
]);
MessageRepo::save($message);

// ACTIONS

$post = \actions\post\GetPost::executeNow([
    'id' => $pageId,
]);
\actions\post\IncrementPostViews::executeNow([
    'post' => $post,
]);

// or

$post['views']++;
\actions\post\Save::executeNow([], $post);

$author = \actions\post\GetPostAuthor::executeNow([
    'postId' => $postId,
])->data ?? null;
\actions\post\NoticeAuthorAboutNewPostView::executeNow([
    'post' => $post,
]);

// and get value

$response = \actions\message\CreateMessageForNoticeAuthorNewPostView::executeNow([
    'post' => $post,
]);
$message = $response->success ? $response->data : null;

// Response

if ($response->success) {
    $response->data;
}
else if ($response->fail) {
    $response->error;
}

?>
<div class="content">
    ..code...
</div>
