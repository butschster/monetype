<?php

return [
    'field'   => [
        'debit'      => 'Плательщик',
        'credit'     => 'Получатель',
        'amount'     => 'Сумма',
        'created_at' => 'Дата платежа',
    ],
    'label'   => [
        'total' => 'Итого:',
    ],
    'paypal'  => [
        'field' => [
            'paymentId' => 'Идентификатор платежа',
            'PayerID'   => 'Идентификатор плательщика',
        ],
    ],
    'message' => [
        'transaction_success'  => 'Ваш счет успешно пополнен на сумму :amount',
        'transaction_canceled' => 'Ваш платеж был отменен',
    ],
];