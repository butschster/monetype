<?php

namespace Modules\Users\Repositories;

use Modules\Users\Model\User;
use Modules\Support\Helpers\Repository;

class UserRepository extends Repository
{

    /**
     * @return string
     */
    public function model()
    {
        return User::class;
    }
}