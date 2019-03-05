<?php

namespace Chivincent\Youku;

use Exception;
use Throwable;

class UploadException extends Exception
{
    /**
     * UploadException constructor.
     *
     * @param string         $message
     * @param int            $code
     * @param Throwable|null $previous
     */
    public function __construct(string $message, int $code, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return string
     */
    public function getError(): string
    {
        return json_encode([
            'error' => [
                'code' => parent::getCode(),
                'message' => parent::getMessage(),
            ],
        ]);
    }
}
