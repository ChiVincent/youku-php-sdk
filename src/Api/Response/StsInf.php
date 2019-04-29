<?php

namespace Chivincent\Youku\Api\Response;

use Chivincent\Youku\Contracts\JsonResponse;
use Chivincent\Youku\Exception\UploadException;

class StsInf extends BaseResponse implements JsonResponse
{
    /**
     * @var string
     */
    private $uploadToken;

    /**
     * @var string
     */
    private $endpoint;

    /**
     * @var string
     */
    private $securityToken;

    /**
     * @var string
     */
    private $tempAccessId;

    /**
     * @var string
     */
    private $tempAccessSecret;

    /**
     * @var string
     */
    private $expireTime;

    /**
     * Make StsInf Response by json.
     *
     * @param string $json
     * @return BaseResponse|null
     */
    public static function json(string $json): ?BaseResponse
    {
        $response = json_decode($json);

        if (isset($response->error)) {
            throw new UploadException($json);
        }

        $properties = [
            'upload_token', 'endpoint', 'security_token', 'temp_access_id', 'temp_access_secret', 'expire_time'
        ];

        foreach ($properties as $property) {
            if (!property_exists($response, $property)) {
                return null;
            }
        }

        return new self(
            $response->upload_token,
            $response->endpoint,
            $response->security_token,
            $response->temp_access_id,
            $response->temp_access_secret,
            $response->expire_time
        );
    }

    /**
     * StsInf constructor.
     *
     * @param string $uploadToken
     * @param string $endpoint
     * @param string $securityToken
     * @param string $tempAccessId
     * @param string $tempAccessSecret
     * @param string $expireTime
     */
    public function __construct(
        string $uploadToken,
        string $endpoint,
        string $securityToken,
        string $tempAccessId,
        string $tempAccessSecret,
        string $expireTime
    ) {
        $this->uploadToken = $uploadToken;
        $this->endpoint = $endpoint;
        $this->securityToken = $securityToken;
        $this->tempAccessId = $tempAccessId;
        $this->tempAccessSecret = $tempAccessSecret;
        $this->expireTime = $expireTime;
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
    public function getEndpoint(): string
    {
        return $this->endpoint;
    }

    /**
     * @return string
     */
    public function getSecurityToken(): string
    {
        return $this->securityToken;
    }

    /**
     * @return string
     */
    public function getTempAccessId(): string
    {
        return $this->tempAccessId;
    }

    /**
     * @return string
     */
    public function getTempAccessSecret(): string
    {
        return $this->tempAccessSecret;
    }

    /**
     * @return string
     */
    public function getExpireTime(): string
    {
        return $this->expireTime;
    }
}
