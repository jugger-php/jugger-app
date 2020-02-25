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
    <link rel="stylesheet" href="/assets/themes/index/style.css">
</head>
<body>
    <h1>
        Заголовок: <?= $title ?>
    </h1>
    <p>
        <?= $context->getContent() ?>
    </p>
    <script src="/assets/themes/index/script.js"></script>
</body>
</html>