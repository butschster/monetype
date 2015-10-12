<?php

namespace Modules\Users\Http\Controllers;

use Modules\Users\Repositories\UserRepository;
use Modules\Core\Http\Controllers\System\FrontController;

class AccountController extends FrontController
{

    public function coupon()
    {
        return $this->setLayout('user.coupon');
    }


    public function add()
    {

    }
}
