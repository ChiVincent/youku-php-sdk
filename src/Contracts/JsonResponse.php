<?php

namespace Chivincent\Youku\Contracts;

use Chivincent\Youku\Api\Response\BaseResponse;

interface JsonResponse
{
    public static function json(string $json): ?BaseResponse;
}