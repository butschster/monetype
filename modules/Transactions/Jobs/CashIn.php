<?php

namespace Modules\Transactions\Jobs;

use DB;
use Modules\Transactions\Model\PaymentMethod;
use Modules\Users\Model\User;
use Illuminate\Contracts\Bus\SelfHandling;
use Modules\Transactions\Model\Transaction;

class CashIn implements SelfHandling
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
        $this->paymentMethod = $method;
        $this->amount        = $amount;
    }


    /**
     * @return Transaction
     */
    public function handle()
    {
        return DB::transaction(function () {
            $transaction = new Transaction;

            $transaction->amount = $this->amount;

            $transaction->assignPurchaser(User::getCreditUser());
            $transaction->assignRecipient($this->user);

            $transaction->setType('cashin');
            $transaction->setStatus('new');
            $transaction->setPaymentMethod($this->paymentMethod);
            $transaction->save();

            $transaction->complete();

            return $transaction;
        });
    }
}