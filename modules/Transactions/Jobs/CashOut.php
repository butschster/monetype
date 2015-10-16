<?php

namespace Modules\Transactions\Jobs;

use DB;
use Modules\Users\Model\User;
use Illuminate\Contracts\Bus\SelfHandling;
use Modules\Transactions\Model\Transaction;
use Modules\Transactions\Exceptions\NotEnoughMoneyException;

class CashOut implements SelfHandling
{

    /**
     * @var User
     */
    protected $user;

    /**
     * @var float
     */
    protected $amount;

    /**
     * @var PaymentMethod
     */
    protected $paymentMethod;


    /**
     * @param User          $user
     * @param PaymentMethod $method
     * @param float         $amount
     */
    public function __construct(User $user, PaymentMethod $method, $amount)
    {
        floatval($amount);
        $this->user          = $user;
        $this->amount        = $amount;
        $this->paymentMethod = $method;
    }


    /**
     * @return Transaction
     * @throws NotEnoughMoneyException
     */
    public function handle()
    {
        if ($this->user->account->balance < $this->amount) {
            throw new NotEnoughMoneyException;
        }

        return DB::transaction(function () {
            $transaction = new Transaction;

            $transaction->amount = $this->amount;

            $transaction->assignPurchaser($this->user);
            $transaction->assignRecipient(User::getDebitUser());

            $transaction->setType('cashout');
            $transaction->setStatus('new');
            $transaction->setPaymentMethod('account');
            $transaction->save();

            $transaction->complete();

            return $transaction;
        });
    }
}