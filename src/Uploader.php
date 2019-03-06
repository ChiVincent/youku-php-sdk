<?php

namespace Chivincent\Youku;

use GuzzleHttp\Client;
use Chivincent\Youku\Api\Api;

class Uploader
{
    /**
     * @var Api
     */
    private $api;

    /**
     * @var string
     */
    private $clientId;

    /**
     * @var string
     */
    private $accessToken;

    public function __construct(string $clientId, string $accessToken)
    {
        $this->clientId = $clientId;
        $this->accessToken = $accessToken;

        $this->api = new Api(new Client());
    }
}
