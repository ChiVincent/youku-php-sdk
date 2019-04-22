<?php

namespace Chivincent\Youku\Api\Response;

use Chivincent\Youku\Contracts\JsonResponse;

class Cancel extends BaseResponse implements JsonResponse
{
    /**
     * @var string
     */
    private $uploadToken;

    /**
     * Make Cancel Response by json.
     *
     * @param string $json
     * @return Cancel|null
     */
    public static function json(string $json): ?BaseResponse
    {
        $response = json_decode($json);

        if (!property_exists($response, 'upload_token')) {
            return null;
        }

        return new Cancel($response->upload_token);
    }

    /**
     * Cancel constructor.
     *
     * @param string $uploadToken
     */
    public function __construct(string $uploadToken)
    {
        $this->uploadToken = $uploadToken;
    }

    /**
     * @return string
     */
    public function getUploadToken(): string
    {
        return $this->uploadToken;
    }
}
