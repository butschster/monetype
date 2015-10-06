<?php

namespace Modules\Users\Http\Controllers\Auth;

use Illuminate\Foundation\Auth\ResetsPasswords;
use Modules\Core\Http\Controllers\System\FrontController;

class PasswordController extends FrontController
{

    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;


    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function boot()
    {
        $this->middleware('guest');
    }


    /**
     * Display the form to request a password reset link.
     *
     * @return Response
     */
    public function getEmail()
    {
        return $this->setLayout('auth.password');
    }
}
