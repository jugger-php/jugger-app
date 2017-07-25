<?php

/*
 * ПРИ РАБОТЕ СО СТРАНИЦАМИ (запросами, сервисами, не важно) У НАС НЕТ НИЧЕГО
 * ТОЛЬКО:
 *      - Request (params, data, cookies, uri, userAgent)
 *      - Di (components, в том числе и session)
 * НИ МАППЕРА, НИ РЕПОЗИТОРИЯ, НИ БАЗЫ (!), НИЧЕГО...
 *
 * РАЗРАБОТКА ДОЛЖНА ПРОВОДИТЬСЯ ИЗ РАСЧЕТА, ЧТО У НАС ЕСТЬ ТОЛЬКО СЕРВИСЫ (МИКРО-СЕРВИСЫ) И НИЧЕГО БОЛЬШЕ.
 * РАЗРАБОТКА ДОЛЖНА СВОДИТЬСЯ ТОЛЬКО К ВЫЗОВУ API КОИМ ЯВЛЯЮТСЯ 'ACTIONS' И РАБОТЫ С ui,
 * 
 * ПРИ РАЗРАБОТКЕ не СЕРВИСА, МЫ НИЧЕГО НЕ ЗНАЕМ ПРО БАЗУ ИЛИ ЕЕ СТРУКТУРУ,
 * МЫ ЗНАЕМ ТОЛЬКО СПИСОК МЕТОДОВ api И ИХ ВХОДНЫЕ И ВЫХОДНЫЕ ПАРАМЕТРЫ
 *
 * ПРИ РАЗРАБОТКЕ СЕРВИСА, МЫ НИЧЕГО НЕ ЗНАЕМ ПРО ui ИЛИ КАКИЕ СТРАНИЦЫ (ВИДЖЕТЫ, ПРЕДСТАВЛЕНИЯ, ...) ЕСТЬ,
 * МЫ ЗНАЕМ ТОЛЬКО ПРО БАЗУ И БИЗНЕС-ЛОГИКУ ОТНОСЯЩУЮСЯ К ТЕКУЩЕМУ КОНТЕКСТУ, А ТАКЖЕ ДРУГИЕ СЕРВИСЫ (ИХ api)
 * 
 * СОБСТВЕННО ЭТО ДАЕТ НАМ АБСОЛЮТНУЮ НЕЗАВИСИМОСТЬ МЕЖДУ МОДУЛЯМИ,
 * Т.К. ОНИ НИЧЕГО НЕ ЗНАЮТ ДРУГ О ДРУГЕ, КРОМЕ api.
 * НА ВСЕ ЭТО ОЧЕНЬ ХОРОШО ЛОЖИТЬСЯ ddd, ТОЛЬКО БЕЗ РЕПОЗИТОРИЕВ И ПРОЧЕЙ ШЛЯПЫ
 *
 * ДОБАВИТЬ ВОМЗОЖНОСТЬ ОТКАТА ДЛЯ ДЕЙСТВИЯ (execute & unExecute) ДЛЯ РЕАЛИЗАЦИИ ТРАНЗАКЦИЙ -- видимо не судьба, т.к. все строиться на API
 */


// Request - должен понимать когда происходит SelfRequest, а когда GlobalRequest
Request::send() = function($url, $data) {
    // local request (как в Yii2)
    if (is_array($url)) {
        $route = $url[0];
        if ($route == true) {
            // ... self request code ...
        }
        else {
            // ... curl code ...
        }
    }
    else {
        // ... curl code ...
    }
}



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

$post = Request::send(['post/get', 'id' => $pageId]);
Request::send([
    'post/add.view',
    'postId' => $postId,
    'userId' => null,
]);

// or

$post['views']++;
Request::send(['post/save'], $post);

$author = Request::send(['post/getAuthor', 'postId' => $postId])->data ?? null;
$response = Request::send(['notice/addForAuthorByPost'], $post);

// Response

if ($response->success) {
    $response->data;
}
else if ($response->fail) {
    $response->error;
}

// 1. Если сервис существует в локальной видимости - то запрос не делается, сразу идет обращение к сервису
// 2. Если сервис не существует в локальной видимости - то делается CURL запрос

$request = new Request(['/post/get', 'id' => 123]);
$response = $request->send();
$post = $response->data ?? null;
// or
$post = Request::sendNow(['/post/get', 'id' => 123])->data;

// если нужно сделать несколько действий можно использовать пул запросов, которые могут выполняться паралельно

RequestPool::sendNow([
    ['stat/newPost/add', 'pid' => $post->id],
    ['subs/newPost/send', 'pid' => $post->id],
    ['moderation/newPost/add', 'pid' => $post->id],
], $isAsync = true);

?>
<div class="content">
    ..code...
</div>
