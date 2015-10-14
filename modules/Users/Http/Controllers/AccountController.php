<?php

namespace Modules\Users\Http\Controllers;

use Bus;
use Exception;
use Modules\Users\Model\Coupon;
use Modules\Users\Jobs\ApplyCoupon;
use Modules\Core\Http\Controllers\System\FrontController;

class AccountController extends FrontController
{

    /**
     * @return \View
     */
    public function coupon()
    {
        return $this->setLayout('user.coupon');
    }


    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function activateCoupon()
    {
        $this->validate($this->request, [
            'code' => 'required',
        ], [], trans('users::coupon.field'));

        $coupon = Coupon::where('code', $this->request->get('code'))->first();

        if (is_null($coupon)) {
            return $this->errorRedirect(trans('users::coupon.message.not_found'));
        }

        try {
            Bus::dispatch(new ApplyCoupon($this->user, $coupon));
        } catch (Exception $e) {
            return $this->errorRedirect(trans('users::coupon.message.not_activated'));
        }

        return $this->errorRedirect(trans('users::coupon.message.activated'));
    }


    public function add()
    {

    }
}
