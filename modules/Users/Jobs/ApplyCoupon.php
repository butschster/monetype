<?php

namespace Modules\Users\Jobs;

use DB;
use Modules\Users\Exceptions\CouponException;
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
     *
     * @throws CouponException
     * @throws CouponExpiredException
     * @throws NotEnoughMoneyException
     */
    public function handle()
    {
        if ($this->coupon->fromUser->id == $this->user->id) {
            throw new CouponException;
        }

        if ( ! $this->coupon->fromUser->hasMoney($this->coupon->amount)) {
            throw new NotEnoughMoneyException;
        }

        if ($this->coupon->isExpired()) {
            $this->coupon->delete();
            throw new CouponExpiredException;
        }

        return DB::transaction(function () {
            $transaction         = new Transaction;
            $transaction->amount = $this->coupon->amount;

            $transaction->assignPurchaser($this->coupon->fromUser);
            $transaction->assignRecipient($this->user);
            $transaction->setType(Transaction::TYPE_COUPON);
            $transaction->setPaymentMethod('account');

            $transaction->details = [
                'coupon' => $this->coupon->code
            ];

            $transaction->save();

            $transaction->complete(function (Transaction $t) {
                $this->coupon->assignToUser($this->user);
                $this->coupon->delete();
            });

            return $transaction;
        });
    }
}