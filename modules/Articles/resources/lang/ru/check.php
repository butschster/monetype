<?php

return [
    'title'  => [
        'index'   => 'Список проверок статей на плагиат',
        'user' => ':user<br /><small>Список выполненных проверок на плагиат</small>',
        'article' => ':article<br /><small>Список выполненных проверок на плагиат</small>',
        'details' => ':article<br /><small>Детали проверки статьи :date</small>'
    ],
    'field'  => [
        'user_id'    => 'Проверящий',
        'article_id' => 'Статья',
        'created_at' => 'Дата проверки',
        'percent'    => 'Процент плагиата'
    ],
    'button' => [
        'details' => 'Подробнее'
    ],
    'label'  => [
        'plagiarism'   => '<span class="label label-danger">Плагиат</span>',
        'successfully' => '<span class="label label-success">Успешно</span>'
    ],
];