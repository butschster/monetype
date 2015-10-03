<?php

namespace Modules\Transactions\Jobs;

use DB;
use Modules\Users\Model\User;
use Illuminate\Contracts\Bus\SelfHandling;
use Modules\Transactions\Model\Transaction;

class CashOut implements SelfHandling
{

    /**
     * @var User
     */
    protected $user;

    /**
     * @var integer
     */
    protected $amount;

    /**
     * @var PaymentMethod
     */
    protected $paymentMethod;


    /**
     * @param User          $user
     * @param PaymentMethod $method
     * @param integer       $amount
     */
    public function __construct(User $user, PaymentMethod $method, $amount)
    {
        $this->user          = $user;
        $this->amount        = $amount;
        $this->paymentMethod = $method;
    }


    public function handle()
    {
        return DB::transaction(function () {
            $transaction = new Transaction;

            $transaction->amount = $this->amount;

            $transaction->assignPurchaser($this->user);
            $transaction->assignRecipient(User::find(Transaction::ACCOUNT_DEBIT));

            $transaction->setType('cashout');
            $transaction->setStatus('new');
            $transaction->setPaymentMethod('account');
            $transaction->save();

            $transaction->complete();
        });
    }
}