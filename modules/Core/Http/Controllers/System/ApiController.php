<?php

namespace Modules\Core\Http\Controllers\System;

use Illuminate\Auth\Guard;
use Illuminate\Http\Request;
use Modules\Users\Model\User;
use KodiCMS\API\Exceptions\PermissionException;
use KodiCMS\API\Http\Controllers\Controller as BaseApiController;

abstract class ApiController extends BaseApiController
{

    /**
     * @var User;
     */
    protected $user;

    /**
     * @var Request
     */
    protected $request;


    public function __construct()
    {
        app()->call([$this, 'initController']);

        // Execute method boot() on controller execute
        if (method_exists($this, 'boot')) {
            app()->call([$this, 'boot']);
        }
    }


    /**
     * @param Request $request
     * @param Guard   $auth
     */
    public function initController(Request $request, Guard $auth)
    {
        $this->request = $request;
        $this->user    = $auth->user();
    }


    /**
     * @param string $message
     */
    public function setErrorMessage($message)
    {
        $this->responseArray['error_message'] = $message;
    }


    /**
     * @param string       $ability
     * @param string|array $arguments
     * @param string|null  $message
     */
    public function checkPermissions($ability, $arguments = [], $message = null)
    {
        if (is_null($message)) {
            $message = trans('core::core.message.gate_not_allowed');
        }

        if (is_null($this->user) or $this->user->cannot($ability, $arguments)) {
            throw new PermissionException($message);
        }
    }
}
