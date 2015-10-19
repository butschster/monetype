<?php

use Modules\Articles\Model\Article;

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
        'text_source'    => 'Текст',
        'forbid_comment' => 'Запретить комментирование',
        'block_reason'   => 'Причина блокировки',
    ],
    'button'  => [
        'save'    => 'Сохранить',
        'edit'    => 'Редактировать',
        'draft'   => 'В черновики',
        'publish' => 'Опубликовать',
        'approve' => 'Одобрить',
        'block'   => 'Заблокировать',
    ],
    'status'  => [
        Article::STATUS_DRAFT     => '<span class="label label-default">Черновик</span>',
        Article::STATUS_PUBLISHED => '<span class="label label-success">Опубликована</span>',
        Article::STATUS_APPROVED  => '<span class="label label-success"><i class="fa fa-check"></i> Опубликована</span>',
        Article::STATUS_BLOCKED   => '<span class="label label-danger"><i class="fa fa-ban"></i> Заблокирована</span>',
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
        'created'                   => 'Статья добавлена',
        'updated'                   => 'Статья сохранена',
        'published'                 => 'Статья опубликована',
        'drafted'                   => 'Статья сохранена в черновики',
        'approved'                  => 'Статья помещена как проверенная',
        'blocked'                   => 'Статья заблокирована',
        'plagiarism'                => 'Статья не может быть опубликована, т.к. не прошла проверку на плагиат',
        'cant_check_for_plagiarism' => 'В данный момент не получается проверить статью на плагиант, попробуйте опубликовать позже'
    ],
];