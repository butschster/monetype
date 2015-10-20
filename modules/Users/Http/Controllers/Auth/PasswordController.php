<?php

namespace Modules\Users\Http\Controllers\Auth;

use Illuminate\Foundation\Auth\ResetsPasswords;
use Modules\Core\Http\Controllers\System\FrontController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
     * @var string
     */
    public $redirectPath = 'auth/login';


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

    /**
     * Display the password reset view for the given token.
     *
     * @param  string  $token
     * @return \Illuminate\Http\Response
     */
    public function getReset($token = null)
    {
        if (is_null($token)) {
            throw new NotFoundHttpException;
        }

        return $this->setLayout('auth.reset')->with('token', $token);
    }
}
