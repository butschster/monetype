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
     * @var string
     */
    public $redirectPath = '/';


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
        $validator = Validator::make($data, [
            'username' => 'alpha_dash|max:50|unique:users',
            'name'     => 'max:255',
            'email'    => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ], [], trans('users::user.field'));

        $request = $this->request;

        $validator->after(function ($validator) use ($data, $request) {
            $client   = new \GuzzleHttp\Client();
            $response = $client->post('https://www.google.com/recaptcha/api/siteverify', [
                'verify' => false,
                'query'  => [
                    'secret'   => '6LexrAMTAAAAAAlyG_SQCUNegF7Wvh3Lqe03_-vD',
                    'response' => array_get($data, 'g-recaptcha-response'),
                    'remoteip' => $request->ip()
                ]
            ]);

            if (array_get($response->json(), 'success') === false) {
                $validator->errors()->add('captcha', trans('validation.custom.captcha.not_valid'));
            }
        });

        return $validator;
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
            'username' => array_get($data, 'username'),
            'name'     => array_get($data, 'name'),
            'email'    => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }
}
