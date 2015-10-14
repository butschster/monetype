<?php

return [
    'title'   => [
        'list'   => 'Список статей',
        'create' => 'Новая статья',
        'by_tag' => ':tag<br /><small>Статьи с тегом</small>',
        'money'  => ':article<br /><small>Собраные деньги</small>',
    ],
    'field'   => [
        'author'         => 'Автор',
        'tags'           => 'Теги',
        'title'          => 'Заголовок',
        'text_intro'     => 'Вводный текст',
        'text'           => 'Текст',
        'forbid_comment' => 'Запретить комментирование',
        'block_reason'   => 'Причина блокировки',
    ],
    'button'  => [
        'draft' => 'Сохранить черновик',
    ],
    'label'   => [
        'balance'        => '<i class="fa fa-money"></i> :amount <i class="fa fa-rub"></i>',
        'cost'           => ':amount <i class="fa fa-rub"></i>',
        'total_amount'   => 'Собрано денег',
        'count_payments' => 'Всего платежей',
    ],
    'message' => [
        'not_enough_money'          => 'У вас недостаточно денег для прочтения данной статьи',
        'can_publish_only_draft'    => 'Опубликовать можно только черновик',
        'can_approve_ony_published' => 'Проверить можно только опубликованную статью',
        'empty_list'                => 'На данный момент не написано ни одной статьи',
        'not_allowed'               => 'У вас нет прав.',
        'created'                   => 'Статья добавлена',
        'updated'                   => 'Статья сохранена',
        'published'                 => 'Статья опубликована',
        'drafted'                   => 'Статья сохранена в черновики',
        'approved'                  => 'Статья помещена как проверенная',
        'blocked'                   => 'Статья заблокирована',
    ],
];