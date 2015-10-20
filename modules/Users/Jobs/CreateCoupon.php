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
     * @var string|null
     */
    protected $expiredAt;

    /**
     * @var string
     */
    protected $type;


    /**
     * @param User        $user
     * @param integer     $amount
     * @param string      $type
     * @param string|null $expiredAt
     */
    public function __construct(User $user, $amount, $type, $expiredAt = null)
    {
        $this->user      = $user;
        $this->amount    = $amount;
        $this->type      = $type;
        $this->expiredAt = empty( $expiredAt ) ? null : $expiredAt;
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
            $coupon             = new Coupon;
            $coupon->amount     = $this->amount;
            $coupon->expired_at = $this->expiredAt;

            $coupon->setType($this->type);
            $coupon->assignFromUser($this->user);

            $coupon->save();

            return $coupon;
        });
    }
}