<?php

namespace Modules\Users\Repositories;

use Modules\Users\Model\User;
use Modules\Users\Model\Coupon;
use Modules\Support\Helpers\Repository;

class CouponRepository extends Repository
{

    /**
     * @return string
     */
    public function model()
    {
        return Coupon::class;
    }

    public function paginateByUser(User $user, $perPage = 15, $columns = ['*'])
    {
        return $this->getModel()
            ->filterByUser($user)
            ->paginate($perPage, $columns);
    }
}