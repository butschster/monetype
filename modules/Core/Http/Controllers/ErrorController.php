<?php

namespace Modules\Core\Http\Controllers;

use Exception;
use Modules\Core\Http\Controllers\System\FrontController;

class ErrorController extends FrontController
{
    /**
     * @param \Exception|null $exception
     */
    public function error500($exception = null)
    {
        $code = is_null($exception)
            ? 500
            : method_exists($exception, 'getStatusCode')
                ? $exception->getStatusCode()
                : $exception->getCode();

        $message = is_null($exception) ? trans('core::error.message.something_went_wrong') : $exception->getMessage();

        $this->setLayout('errors.500', [
            'error'   => $exception,
            'code'    => $code,
            'message' => $message,
        ]);
    }
}
