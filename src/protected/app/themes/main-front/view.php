<?php

use app\widgets\menu\Menu;
use jugger\core\Renderer;
use jugger\widget\Theme;

/**
 * @var Renderer $this
 * @var Theme $context
 */

$assets = $context->getAssets();
$assets->addJs([
    'src' => 'https://code.jquery.com/jquery-3.4.1.min.js',
]);
$assets->addJs("https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js");
$assets->addJs("https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js");
$assets->addCss("https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css");

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>
        Заголовок
    </title>
</head>
<body>
    <header>
        <div class="container">
            <?= $context->widget(Menu::class, 'bootstrap-navbar', [
                'brand' => 'Logo',
                'items' => [
                    'Главная' => '/',
                    'Компания' => '/about',
                    'Услуги' => '/services',
                    'Портфолио' => '/portfolio',
                    'Контакты' => '/contacts',
                ],
                'activeLink' => "/{$context->getRequest()->getPath()}",
            ]) ?>
        </div>
        <div class="jumbotron jumbotron-fluid">
            <div class="container">
                <h1 class="display-4">
                    Заголовок
                </h1>
                <p class="lead">
                    баннер на главной
                </p>
            </div>
        </div>
    </header>
    <main>
        <div class="container">
            <?= $context->getContent() ?>
        </div>
    </main>
    <footer class="footer bg-dark text-light">
        <div class="container footerContainer">
            <div class="footerContainer__col">
                лого и копирайт
            </div>
            <div class="footerContainer__col">
                меню
            </div>
            <div class="footerContainer__col">
                ссылки и контакты
            </div>
        </div>
    </footer>
</body>
</html>