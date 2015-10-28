<?php

namespace Modules\Core\Http;

use KodiCMS\API\Http\Response;
use KodiCMS\API\Exceptions\Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\MassAssignmentException;

class ApiResponse extends Response
{

    /**
     * Creates the error Response associated with the given Exception.
     *
     * @param \Exception $exception
     *
     * @return Response A Response instance
     */
    public function createExceptionResponse(\Exception $exception)
    {
        $responseData = [
            'code' => $exception->getCode(),
            'type' => static::TYPE_ERROR,
        ];

        if (config('app.debug')) {
            $responseData['error_message'] = $exception->getMessage();
        } else {
            $responseData['error_message'] = trans('core::core.message.something_went_wrong');
        }

        if ($exception instanceof Exception or method_exists($exception, 'responseArray')) {
            $responseData = array_merge($responseData, $exception->responseArray());
        } else {
            if ($exception instanceof ModelNotFoundException) {
                $responseData['code'] = static::ERROR_PAGE_NOT_FOUND;
            } else {
                if ($exception instanceof MassAssignmentException) {
                    $responseData['code']  = static::ERROR_MISSING_ASSIGMENT;
                    $responseData['field'] = $exception->getMessage();
                }
            }
        }

        if (config('app.debug')) {
            $responseData['file'] = $exception->getFile();
            $responseData['line'] = $exception->getLine();
        }

        return $this->createResponse($responseData, 500);
    }

}