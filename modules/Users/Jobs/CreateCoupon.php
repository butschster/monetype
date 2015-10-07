<?php

namespace Modules\Users\Jobs;

use DB;
use Carbon\Carbon;
use Modules\Users\Model\User;
use Modules\Users\Model\Coupon;
use Illuminate\Contracts\Bus\SelfHandling;
use Modules\Transactions\Model\Transaction;

class CreateCoupon implements SelfHandling
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
     * @var string|Carbon
     */
    protected $expiredAt;


    /**
     * @param User          $user
     * @param integer       $amount
     * @param string|Carbon $expiredAt
     */
    public function __construct(User $user, $amount, $expiredAt = null)
    {
        $this->user      = $user;
        $this->amount    = $amount;

        $this->expiredAt = ( $expiredAt instanceof Carbon )
            ? $expiredAt
            : Carbon::parse($expiredAt);
    }


    public function handle()
    {
        return DB::transaction(function () {
            $transaction         = new Transaction;
            $transaction->amount = $this->amount;

            $transaction->assignPurchaser($this->user);
            $transaction->assignRecipient(User::find(Transaction::ACCOUNT_CREDIT));
            $transaction->setType('coupon');
            $transaction->setStatus('new');
            $transaction->setPaymentMethod('account');

            $transaction->save();

            $transaction->complete(function (Transaction $t) {
                $coupon             = new Coupon;
                $coupon->amount    = $t->amount;
                $coupon->expired_at = $this->expiredAt;
                $coupon->create();
            });

            return $transaction;
        });
    }
}