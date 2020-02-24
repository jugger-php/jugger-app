<?php

/**
 * Список URL шаблонов
 * 
 * Например:
 *  1. 'page/view/{id}' => 'module/Action'
 *  переменная id будет инициированная в качестве параметров запроса
 *  
 *  2. 'page/view/{code:\w+}' => 'module/path/to/ActionName'
 *  в переменной code будет храниться строка удовлетворяющая регулярке '\w+'
 * 
 * @var array
 */
return [
    'cms' => 'cms/index',
    'cms/{path}' => 'cms/page',
    'blog/{code}' => 'blog/view',
    'blog' => 'blog/list',
    'solutions/{code}' => 'solutions/view',
    'solutions' => 'solutions/index',
    '{url}' => 'page/view',
    '' => 'page/index',
];