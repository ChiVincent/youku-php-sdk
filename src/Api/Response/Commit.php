<?php

namespace Chivincent\Youku\Api\Response;

class Commit
{
    /**
     * @var string
     */
    private $videoId;

    /**
     * Make Commit Response by json.
     *
     * @param string $json
     * @return Commit|null
     */
    public static function json(string $json): ?Commit
    {
        $response = json_decode($json);

        if (!property_exists($response, 'video_id')) {
            return null;
        }

        return new Commit($response->video_id);
    }

    /**
     * Commit constructor.
     *
     * @param string $videoId
     */
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
