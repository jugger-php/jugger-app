<?php

/**
 * Список URL шаблонов
 * 
 * Например:
 *  1. 'page/view/{id}' => 'module/action'
 *  переменная id будет инициированная в качестве параметров запроса
 *  
 *  2. 'page/view/{code:\w+}' => 'module/path/to/action'
 *  в переменной code будет храниться строка удовлетворяющая регулярке '\w+'
 * 
 * @var array
 */
return [
    '/' => 'main/page',
    '{code}' => 'main/page',
];