<?php

/**
 * @var array $params
 * @var jugger\widget\Theme $context
 * @var jugger\core\Renderer $this
 */

$title = $params['title'] ?? '';

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= $title ?>
    </title>
</head>
<body>
    <h1>
        Заголовок: <?= $title ?>
    </h1>
    <p>
        <?= $context->getContent() ?>
    </p>
</body>
</html>