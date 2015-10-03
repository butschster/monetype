<?php

namespace Modules\Transactions\Observers;

use Modules\Transactions\Model\Transaction;

class TransactionObserver
{

    /**
     * @param Transaction $transaction
     *
     * @return bool
     */
    public function created(Transaction $transaction)
    {

    }
}