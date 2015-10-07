<?php

namespace Modules\Users\Jobs;

use DB;
use Modules\Users\Exceptions\CouponExpiredException;
use Modules\Users\Model\User;
use Modules\Users\Model\Coupon;
use Illuminate\Contracts\Bus\SelfHandling;
use Modules\Transactions\Model\Transaction;
use Modules\Transactions\Exceptions\NotEnoughMoneyException;

class ApplyCoupon implements SelfHandling
{

    /**
     * @var User
     */
    protected $user;

    /**
     * @var Coupon
     */
    protected $coupon;


    /**
     * @param User   $user
     * @param Coupon $coupon
     */
    public function __construct(User $user, Coupon $coupon)
    {
        $this->user   = $user;
        $this->coupon = $coupon;
    }


    /**
     * @return Transaction
     * @throws CouponExpiredException
     * @throws NotEnoughMoneyException
     */
    public function handle()
    {
        if ($this->coupon->user->account->balance < $this->coupon->amount) {
            throw new NotEnoughMoneyException;
        }

        if ($this->coupon->isExpired()) {
            throw new CouponExpiredException;
        }

        return DB::transaction(function () {
            $transaction         = new Transaction;
            $transaction->amount = $this->coupon->amount;

            $transaction->assignPurchaser($this->coupon->user);
            $transaction->assignRecipient($this->user);
            $transaction->setType('coupon');
            $transaction->setStatus('new');
            $transaction->setPaymentMethod('account');

            $transaction->details = [
                'coupon' => $this->coupon->code
            ];

            $transaction->save();

            $transaction->complete(function (Transaction $t) {
                $this->coupon->delete();
            });

            return $transaction;
        });
    }
}