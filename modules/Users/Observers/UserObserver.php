<?php

namespace Modules\Users\Observers;

use Bus;
use Request;
use Modules\Users\Model\User;
use Modules\Users\Model\Coupon;
use Modules\Users\Jobs\ApplyCoupon;

class UserObserver
{

    /**
     * @param User $user
     */
    public function created(User $user)
    {
        if ( ! app()->runningInConsole()) {
            $coupon = Coupon::onlyForRegister()->first();

            if ( ! is_null($coupon)) {
                Bus::dispatch(new ApplyCoupon($user, $coupon));
            }
        }
    }
}