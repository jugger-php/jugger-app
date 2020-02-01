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
    'auth' => 'user/Auth',
    'register' => 'user/Register',
    'blog/get' => 'blog/Get',
    'blog/save' => 'blog/Save',
    'blog/delete' => 'blog/Delete',
    'blog/getlist' => 'blog/GetList',
];