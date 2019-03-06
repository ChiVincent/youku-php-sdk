<?php

namespace Chivincent\Youku\Api\Response;

class Commit
{
    /**
     * @var string
     */
    private $videoId;

    public static function json(string $json): ?Commit
    {
        $response = json_decode($json);

        if (!property_exists($response, 'video_id')) {
            return null;
        }

        return new Commit($response->video_id);
    }

    public function __construct(string $videoId)
    {
        $this->videoId = $videoId;
    }

    /**
     * @return string
     */
    public function getVideoId(): string
    {
        return $this->videoId;
    }
}
