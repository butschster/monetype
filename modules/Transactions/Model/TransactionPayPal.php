<?php

namespace Modules\Transactions\Model;

class TransactionPayPal extends TransactionGateway
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'transactions_paypal';


    /**
     * @return string
     */
    public function getPaymentMethod()
    {
        return 'paypal';
    }
}