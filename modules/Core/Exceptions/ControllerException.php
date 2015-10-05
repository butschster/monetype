<?php

namespace Modules\Core\Exceptions;

use Illuminate\Http\Exception\HttpResponseException;

class ControllerException extends \Exception
{

    protected $nextUrl = null;


    /**
     * @param string $url
     *
     * @return $this
     */
    public function setNextUrl($url)
    {
        $this->nextUrl = $url;

        return $this;
    }


    /**
     * @return null
     */
    public function getNextUrl()
    {
        return $this->nextUrl;
    }


    /**
     * @return $this
     */
    public function redirectBack()
    {
        $this->nextUrl = true;

        return $this;
    }


    public function throwFailException()
    {
        if (is_null($this->getNextUrl())) {
            abort(404, $this->getMessage());
        } else if ($this->getNextUrl() === true) {
            $redirect = back();
        } else {
            $redirect = redirect($this->getNextUrl());
        }

        throw new HttpResponseException($redirect->withErrors($this->getMessage()));
    }
}
