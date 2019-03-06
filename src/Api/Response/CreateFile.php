<?php

namespace Chivincent\Youku\Api\Response;

class CreateFile
{
    public static function json(string $json): ?CreateFile
    {
        $response = json_decode($json);

        return $response
            ? new CreateFile()
            : null;
    }
}
