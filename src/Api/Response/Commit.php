<?php

namespace Chivincent\Youku\Api\Response;

use Chivincent\Youku\Contracts\JsonResponse;

class Commit extends BaseResponse implements JsonResponse
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
    public static function json(string $json): ?BaseResponse
    {
        $response = json_decode($json);

        if (!property_exists($response, 'video_id')) {
            return null;
        }

        return new self($response->video_id);
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
