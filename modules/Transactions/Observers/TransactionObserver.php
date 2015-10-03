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
        $debitAccount = $transaction->debitAccount->account;
        $debitAccount->balance -= $transaction->amount;
        $debitAccount->save();

        $creditAccount = $transaction->creditAccount->account;
        $creditAccount->balance += $transaction->amount;
        $creditAccount->save();
    }
}