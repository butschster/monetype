<?php

namespace Modules\Articles\Exceptions;

use Modules\Articles\Model\ArticleCheck;

class PlagiatException extends \Exception
{

    /**
     * @var ArticleCheck
     */
    protected $check;

    public function __construct(ArticleCheck $check, $message = null, $code = 0, Exception $previous = null)
    {
        $this->check = $check;

        parent::__construct($message, $code, $previous);
    }
}