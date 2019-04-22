<?php

namespace Chivincent\Youku\Api\Response;

use Chivincent\Youku\Contracts\JsonResponse;

class RefreshToken extends BaseResponse implements JsonResponse
{
    /**
     * @var string
     */
    private $accessToken;

    /**
     * @var int
     */
    private $expiresIn;

    /**
     * @var string
     */
    private $refreshToken;

    /**
     * @var string
     */
    private $tokenType;

    /**
     * Make RefreshToken Response by json.
     *
     * @param string $json
     * @return RefreshToken|null
     */
    public static function json(string $json): ?BaseResponse
    {
        $response = json_decode($json);

        $properties = [
            'access_token', 'expires_in', 'refresh_token', 'token_type',
        ];

        foreach ($properties as $property) {
            if (!property_exists($response, $property)) {
                return null;
            }
        }

        return new RefreshToken(
            $response->access_token,
            $response->expires_in,
            $response->refresh_token,
            $response->token_type
        );
    }

    /**
     * RefreshToken constructor.
     *
     * @param string $accessToken
     * @param int $expiresIn
     * @param string $refreshToken
     * @param string $tokenType
     */
    public function __construct(string $accessToken, int $expiresIn, string $refreshToken, string $tokenType)
    {
        $this->accessToken = $accessToken;
        $this->expiresIn = $expiresIn;
        $this->refreshToken = $refreshToken;
        $this->tokenType = $tokenType;
    }

    /**
     * @return string
     */
    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    /**
     * @return int
     */
    public function getExpiresIn(): int
    {
        return $this->expiresIn;
    }

    /**
     * @return string
     */
    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }

    /**
     * @return string
     */
    public function getTokenType(): string
    {
        return $this->tokenType;
    }
}
