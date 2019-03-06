<?php

namespace Chivincent\Youku\Api\Response;

class Cancel
{
    /**
     * @var string
     */
    private $uploadToken;

    public static function json(string $json): ?Cancel
    {
        $response = json_decode($json);

        if (!property_exists($response, 'upload_token')) {
            return null;
        }

        return new Cancel($response->upload_token);
    }

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
