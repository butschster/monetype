<?php

use Modules\Articles\Model\Article;

return [
    'title'   => [
        'list'     => 'Список статей',
        'top'      => 'Лучшие статьи',
        'create'   => 'Новая статья',
        'by_tag'   => ':tag<br /><small>Статьи с тегом</small>',
        'money'    => ':article<br /><small>Собраные деньги</small>',
        'search'   => 'Результаты поиска',
        'thematic' => 'Тематические'
    ],
    'field'   => [
        'author'             => 'Автор',
        'tags_list'          => 'Теги',
        'cost'               => 'Стоимость прочтения',
        'title'              => 'Заголовок',
        'text_intro'         => 'Вводный текст',
        'text'               => 'Текст',
        'text_source'        => 'Текст',
        'disable_comments'   => 'Отключить комментарии',
        'disable_stat_views' => 'Не показывать статистику просмотров',
        'disable_stat_pays'  => 'Не показывать статистику покупок',
        'block_reason'       => 'Причина блокировки',
    ],
    'menu'    => [
        'create'   => '<i class="icon-pencil"></i> Написать статью',
        'thematic' => 'Тематические',
        'index'    => 'Все подряд',
        'top'      => 'Лучшие'
    ],
    'button'  => [
        'save'        => 'Сохранить',
        'edit'        => 'Редактировать',
        'draft'       => 'В черновики',
        'publish'     => 'Опубликовать',
        'approve'     => 'Одобрить',
        'block'       => 'Заблокировать',
        'view'        => 'Просмотр',
        'preview'     => 'Редактировать',
        'buy'         => 'Оплатить',
        'purchases'   => 'Платежи',
        'checks'      => 'Проверки на плагиат',
        'new_comment' => 'Комментировать'
    ],
    'status'  => [
        Article::STATUS_DRAFT     => '<span class="label label-default">Черновик</span>',
        Article::STATUS_PUBLISHED => '<span class="label label-success">Опубликована</span>',
        Article::STATUS_APPROVED  => '<span class="label label-success"><i class="icon-ok"></i> Опубликована</span>',
        Article::STATUS_BLOCKED   => '<span class="label label-danger"><i class="icon-ban"></i> Заблокирована</span>',
    ],
    'label'   => [
        'cost'           => ':amount <i class="icon-rouble"></i>',
        'balance'        => '<i class="icon-briefcase"></i> :amount <i class="icon-rouble"></i>',
        'total_amount'   => 'Собрано денег',
        'count_payments' => 'Всего платежей',
        'free'           => 'Бесплатно',
        'reading_time'   => ':minutes мин',
        'search'         => 'Поиск'
    ],
    'message' => [
        'need_to_buy'               => 'Вам необходимо заплатить :amount <i class="icon-rouble"></i> для прочтения статьи.',
        'need_to_register'          => '<i class="icon-emo-wink"></i> Для прочтения статьи вы должны быть авторизованы, если у вас нет аккаунта, вам необходимо зарегистрироваться',
        'not_enough_money'          => '<i class="icon-emo-unhappy"></i> У вас недостаточно денег для прочтения данной статьи',
        'can_publish_only_draft'    => 'Опубликовать можно только черновик',
        'can_approve_ony_published' => 'Проверить можно только опубликованную статью',
        'empty_list'                => '<i class="icon-emo-unhappy"></i> На данный момент не написано ни одной статьи',
        'empty_thematic_list'       => '<i class="icon-emo-unhappy"></i> На данный момент не написано ни одной статьи по вашей тематике',
        'no_thematic_tags'          => 'У вас не указано ни одного тематического тега',
        'bought'                    => '',
        'created'                   => 'Статья добавлена',
        'updated'                   => '<i class="icon-emo-happy"></i> Статья сохранена',
        'published'                 => 'Статья опубликована',
        'drafted'                   => 'Статья сохранена в черновики',
        'approved'                  => 'Статья помещена как проверенная',
        'blocked'                   => 'Статья заблокирована',
        'no_search_results'         => '<i class="icon-emo-unhappy"></i> По вашему запросу ничего не найдено',
        'plagiarism'                => '<i class="icon-emo-unhappy"></i> Статья не может быть опубликована, т.к. не прошла проверку на плагиат',
        'cant_check_for_plagiarism' => '<i class="icon-emo-unhappy"></i> В данный момент не получается проверить статью на плагиант, попробуйте опубликовать позже',
    ],
];