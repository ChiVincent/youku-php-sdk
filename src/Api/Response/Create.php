<?php

namespace Chivincent\Youku\Api\Response;

class Create
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

    public static function json(string $json): ?Create
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

        return new Create(
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
