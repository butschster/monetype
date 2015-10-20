<?php

namespace Modules\Core\Http\Controllers;

use Auth;
use Lang;
use Assets;
use Modules\Users\Model\Coupon;
use Modules\Core\Http\Controllers\System\FrontController;

class SoonController extends FrontController
{

    public function registerMedia()
    {
        $this->templateScripts = [
            'SITE_URL'        => url(),
            'LOCALE'          => Lang::getLocale(),
            'USER_ID'         => Auth::id(),
            'URL'             => $this->request->url(),
            'MESSAGE_ERRORS'  => view()->shared('errors')->getBag('default'),
            'MESSAGE_SUCCESS' => (array) $this->session->get('success', []),
        ];

        Assets::package(['libraries', 'coming_soon', 'backstretch', 'countdown']);
    }


    public function index()
    {
        return $this->setLayout('comingsoon.index', [
            'couponsCount' => Coupon::onlyForRegister()->count()
        ]);
    }
}
