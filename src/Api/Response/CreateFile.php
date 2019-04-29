<?php

namespace Chivincent\Youku\Api\Response;

use Chivincent\Youku\Contracts\JsonResponse;
use Chivincent\Youku\Exception\UploadException;

class CreateFile extends BaseResponse implements JsonResponse
{
    /**
     * Make CreateFile Response by json.
     *
     * @param string $json
     * @return CreateFile|null
     */
    public static function json(string $json): ?BaseResponse
    {
        $response = json_decode($json);

        if (isset($response->error)) {
            throw new UploadException(Error::json($json));
        }

        return $response
            ? new self()
            : null;
    }
}
