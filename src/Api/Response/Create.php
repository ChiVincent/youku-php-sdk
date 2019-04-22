<?php

namespace Chivincent\Youku\Api\Response;

use Chivincent\Youku\Contracts\JsonResponse;

class Create extends BaseResponse implements JsonResponse
{
    /**
     * @var string
     */
    private $uploadToken;

    /**
     * @var string
     */
    private $videoId;

    /**
     * @var string
     */
    private $uploadServerUri;

    /**
     * Make Create Response by json.
     *
     * @param string $json
     * @return Create|null
     */
    public static function json(string $json): ?BaseResponse
    {
        $response = json_decode($json);

        $properties = [
            'upload_token', 'video_id', 'upload_server_uri',
        ];

        foreach ($properties as $property) {
            if (!property_exists($response, $property)) {
                return null;
            }
        }

        return new self(
            $response->upload_token,
            $response->video_id,
            $response->upload_server_uri
        );
    }

    public function __construct(string $uploadToken, string $videoId, string $uploadServerUri)
    {
        $this->uploadToken = $uploadToken;
        $this->videoId = $videoId;
        $this->uploadServerUri = $uploadServerUri;
    }

    /**
     * @return string
     */
    public function getUploadToken(): string
    {
        return $this->uploadToken;
    }

    /**
     * @return string
     */
    public function getVideoId(): string
    {
        return $this->videoId;
    }

    /**
     * @return string
     */
    public function getUploadServerUri(): string
    {
        return $this->uploadServerUri;
    }
}
