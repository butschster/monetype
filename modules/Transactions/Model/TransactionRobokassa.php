<?php

namespace Modules\Transactions\Model;

class TransactionRobokassa extends TransactionGateway
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'transactions_robokassa';

    /**
     * @return string
     */
    public function getPaymentMethod()
    {
        return 'robokassa';
    }

}