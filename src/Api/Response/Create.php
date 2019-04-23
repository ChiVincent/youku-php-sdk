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
     * @var null|string
     */
    private $uploadServerUri;

    /**
     * @var null|string
     */
    private $endpoint;

    /**
     * @var null|string
     */
    private $securityToken;

    /**
     * @var null|string
     */
    private $ossBucket;

    /**
     * @var null|string
     */
    private $ossObject;

    /**
     * @var null|string
     */
    private $tempAccessId;

    /**
     * @var null|string
     */
    private $tempAccessSecret;

    /**
     * @var null|string
     */
    private $expireTime;

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
            'upload_token', 'video_id',
        ];

        foreach ($properties as $property) {
            if (!property_exists($response, $property)) {
                return null;
            }
        }

        return new self(
            $response->upload_token,
            $response->video_id,
            $response->upload_server_uri ?? null,
            $response->endpoint ?? null,
            $response->security_token ?? null,
            $response->oss_bucket ?? null,
            $response->oss_object ?? null,
            $response->temp_access_id ?? null,
            $response->temp_access_secret ?? null,
            $response->expire_time ?? null
        );
    }

    public function __construct(
        string $uploadToken,
        string $videoId,
        ?string $uploadServerUri = null,
        ?string $endpoint = null,
        ?string $securityToken = null,
        ?string $ossBucket = null,
        ?string $ossObject = null,
        ?string $tempAccessId = null,
        ?string $tempAccessSecret = null,
        ?string $expireTime = null
    ) {
        $this->uploadToken = $uploadToken;
        $this->videoId = $videoId;
        $this->uploadServerUri = $uploadServerUri;
        $this->endpoint = $endpoint;
        $this->securityToken = $securityToken;
        $this->ossBucket = $ossBucket;
        $this->ossObject = $ossObject;
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
    public function getVideoId(): string
    {
        return $this->videoId;
    }

    /**
     * @return string|null
     */
    public function getEndpoint(): ?string
    {
        return $this->endpoint;
    }

    /**
     * @return string|null
     */
    public function getSecurityToken(): ?string
    {
        return $this->securityToken;
    }

    /**
     * @return string|null
     */
    public function getOssBucket(): ?string
    {
        return $this->ossBucket;
    }

    /**
     * @return string|null
     */
    public function getOssObject(): ?string
    {
        return $this->ossObject;
    }

    /**
     * @return string|null
     */
    public function getTempAccessId(): ?string
    {
        return $this->tempAccessId;
    }

    /**
     * @return string|null
     */
    public function getTempAccessSecret(): ?string
    {
        return $this->tempAccessSecret;
    }

    /**
     * @return string|null
     */
    public function getExpireTime(): ?string
    {
        return $this->expireTime;
    }

    /**
     * @return null|string
     */
    public function getUploadServerUri(): ?string
    {
        return $this->uploadServerUri;
    }
}
