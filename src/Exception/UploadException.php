<?php

namespace Chivincent\Youku\Exception;

use Chivincent\Youku\Api\Response\Error;
use Exception;
use Throwable;

class UploadException extends Exception
{
    public function __construct(?Error $error, Throwable $previous = null)
    {
        $error === null
            ? parent::__construct('Empty Response', 0, $previous)
            : parent::__construct($error->getDescription(), $error->getCode(), $previous);
    }
}
