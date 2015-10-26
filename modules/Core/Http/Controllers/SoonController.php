<?php

namespace Modules\Core\Http\Controllers;

use Auth;
use Lang;
use Meta;
use Modules\Users\Model\Coupon;
use Modules\Core\CommingSoonSocialTags;
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
            'MESSAGE_SUCCESS' => (array) $this->session->get('success', [])
        ];

        Meta::addPackage(['libraries', 'coming_soon', 'backstretch', 'countdown', 'validation']);
    }


    public function index()
    {
        Meta::addSocialTags(new CommingSoonSocialTags);
        $this->setTitle(null);

        return $this->setLayout('comingsoon.index', [
            'couponsCount' => Coupon::onlyForRegister()->count()
        ]);
    }
}
