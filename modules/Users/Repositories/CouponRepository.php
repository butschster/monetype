<?php

namespace Modules\Users\Repositories;

use Modules\Users\Model\User;
use Modules\Users\Model\Coupon;
use Modules\Support\Helpers\Repository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CouponRepository extends Repository
{

    /**
     * @return string
     */
    public function model()
    {
        return Coupon::class;
    }


    /**
     * @param User  $user
     * @param int   $perPage
     * @param array $columns
     *
     * @return LengthAwarePaginator
     */
    public function paginateByUser(User $user, $perPage = 15, $columns = ['*'])
    {
        return $this->getModel()
            ->onlyUsers()
            ->filterByUser($user)
            ->paginate($perPage, $columns);
    }
}