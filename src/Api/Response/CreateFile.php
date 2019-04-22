<?php

namespace Chivincent\Youku\Api\Response;

class CreateFile
{
    /**
     * Make CreateFile Response by json.
     *
     * @param string $json
     * @return CreateFile|null
     */
    public static function json(string $json): ?CreateFile
    {
        $response = json_decode($json);

        return $response
            ? new CreateFile()
            : null;
    }
}
