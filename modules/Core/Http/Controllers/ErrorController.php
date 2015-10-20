<?php

namespace Modules\Core\Http\Controllers;

use Exception;
use Modules\Core\Http\Controllers\System\FrontController;

class ErrorController extends FrontController
{

    /**
     * @param \Exception|null $exception
     *
     * @return \View
     */
    public function error500($exception = null)
    {
        return $this->setLayout('errors.code', [
            'error'   => $exception,
            'code'    => $this->getCode($exception),
            'message' => $this->getMessage($exception),
        ]);
    }


    /**
     * @param \Exception|null $exception
     *
     * @return \View
     */
    public function error403($exception = null)
    {
        return $this->setLayout('errors.code', [
            'error'   => $exception,
            'code'    => $this->getCode($exception),
            'message' => trans('core::core.message.gate_not_allowed'),
        ]);
    }


    /**
     * @param \Exception|null $exception
     *
     * @return \View
     */
    public function error404($exception = null)
    {
        return $this->setLayout('errors.code', [
            'error'   => $exception,
            'code'    => $this->getCode($exception),
            'message' => trans('core::core.message.page_not_found'),
        ]);
    }


    /**
     * @param \Exception|null $exception
     *
     * @return \View
     */
    public function errorDefault($exception = null)
    {
        return $this->setLayout('errors.code', [
            'code'    => 500,
            'error'   => $exception,
            'message' => $this->getMessage($exception),
        ]);
    }


    /**
     * @param Exception|null $exception
     *
     * @return string
     */
    private function getCode($exception)
    {
        if (is_null($exception)) {
            return 500;
        }

        return method_exists($exception, 'getStatusCode') ? $exception->getStatusCode() : $exception->getCode();
    }


    /**
     * @param Exception|null $exception
     *
     * @return string
     */
    private function getMessage($exception)
    {
        $message = null;

        if ( ! is_null($exception)) {
            $message = $exception->getMessage();
        }

        if (empty( $message )) {
            $message = trans('core::core.message.something_went_wrong');
        }

        return $message;
    }
}
