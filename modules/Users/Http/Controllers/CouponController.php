<?php

namespace Modules\Users\Http\Controllers;

use Bus;
use Exception;
use Modules\Users\Jobs\ApplyCoupon;
use Modules\Users\Jobs\CreateCoupon;
use Modules\Users\Repositories\CouponRepository;
use Modules\Core\Http\Controllers\System\FrontController;
use Modules\Transactions\Exceptions\NotEnoughMoneyException;

class CouponController extends FrontController
{

    /**
     * @param CouponRepository $couponRepository
     *
     * @return \View
     */
    public function index(CouponRepository $couponRepository)
    {
        $coupons = $couponRepository->paginateByUser($this->user);

        return $this->setLayout('coupon.index', compact('coupons'));
    }


    /**
     * @param CouponRepository $couponRepository
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function activate(CouponRepository $couponRepository)
    {
        $this->validate($this->request, [
            'code' => 'required',
        ], [], trans('users::coupon.field'));

        $coupon = $couponRepository->findBy('code', $this->request->get('code'));

        if (is_null($coupon)) {
            return $this->errorRedirect(trans('users::coupon.message.not_found'));
        }

        try {
            Bus::dispatch(
                new ApplyCoupon($this->user, $coupon)
            );
        } catch (Exception $e) {
            return $this->errorRedirect(trans('users::coupon.message.not_activated'));
        }

        return $this->successRedirect(trans('users::coupon.message.activated'));
    }


    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create()
    {
        $this->validate($this->request, [
            'amount'     => 'required|numeric|min:1|max:100',
            'expired_at' => 'date',
        ], [], trans('users::coupon.field'));

        try {
            Bus::dispatch(
                new CreateCoupon(
                    $this->user, $this->request->get('amount'), 'user', $this->request->get('expired_at')
                )
            );
        } catch (NotEnoughMoneyException $e) {
            return $this->errorRedirect(trans('users::coupon.message.not_enough_money'));
        } catch (Exception $e) {
            return $this->errorRedirect(trans('users::coupon.message.not_created'));
        }

        return $this->successRedirect(trans('users::coupon.message.created'));
    }


    /**
     * @param CouponRepository $couponRepository
     * @param integer          $couponId
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(CouponRepository $couponRepository, $couponId)
    {
        $coupon = $couponRepository->findOrFail($couponId);

        $this->checkPermissions('delete-coupon', $coupon);

        $couponRepository->delete($couponId);
        return $this->successRedirect(trans('users::coupon.message.deleted'));
    }
}
