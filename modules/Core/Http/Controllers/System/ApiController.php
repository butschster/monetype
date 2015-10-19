<?php

namespace Modules\Core\Http\Controllers\System;

use KodiCMS\API\Exceptions\PermissionException;
use KodiCMS\API\Http\Controllers\Controller as BaseApiController;

abstract class ApiController extends BaseApiController
{

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

        $user = auth()->user();

        if (is_null($user) or $user->cannot($ability, $arguments)) {
            throw new PermissionException($message);
        }
    }
}
