<?php

namespace Modules\Users\Jobs;

use DB;
use Modules\Users\Model\User;
use Modules\Users\Model\Coupon;
use Illuminate\Contracts\Bus\SelfHandling;
use Modules\Transactions\Exceptions\NotEnoughMoneyException;

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
     * @param User    $user
     * @param integer $amount
     */
    public function __construct(User $user, $amount)
    {
        $this->user   = $user;
        $this->amount = $amount;
    }


    /**
     * @return Coupon
     * @throws NotEnoughMoneyException
     */
    public function handle()
    {
        if ($this->user->account->balance < $this->amount) {
            throw new NotEnoughMoneyException;
        }

        return DB::transaction(function () {
            $coupon         = new Coupon;
            $coupon->amount = $this->amount;
            $coupon->assignFromUser($this->user);

            $coupon->save();

            return $coupon;
        });
    }
}