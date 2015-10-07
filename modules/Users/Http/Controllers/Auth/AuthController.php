<?php

namespace Modules\Users\Http\Controllers\Auth;

use Validator;
use Modules\Users\Model\User;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Modules\Core\Http\Controllers\System\FrontController;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class AuthController extends FrontController
{

    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;


    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function boot(Guard $auth)
    {
        $this->auth = $auth;

        $this->middleware('guest', ['except' => 'getLogout']);
    }


    /**
     * Show the application login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function getLogin()
    {
        if ($this->auth->check()) {
            return redirect($this->redirectPath());
        }

        return $this->setLayout('auth.login');
    }


    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function getRegister()
    {
        if ($this->auth->check()) {
            return redirect($this->redirectPath());
        }

        return $this->setLayout('auth.register');
    }


    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name'     => 'required|max:255',
            'email'    => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);
    }


    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     *
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }
}