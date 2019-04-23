<?php

namespace Chivincent\Youku;

use Chivincent\Youku\Api\Response\Commit;
use Chivincent\Youku\Api\Response\Error;
use GuzzleHttp\Client;
use Chivincent\Youku\Api\Api;
use Chivincent\Youku\Api\Response\Check;
use Chivincent\Youku\Api\Response\Create;
use Chivincent\Youku\Api\Response\NewSlice;
use Chivincent\Youku\Api\Response\UploadSlice;
use Chivincent\Youku\Exception\UploadException;
use OSS\Core\OssException;
use OSS\OssClient;

class Uploader
{
    /**
     * @var Api
     */
    protected $api;

    /**
     * @var string
     */
    protected $clientId;

    /**
     * @var string
     */
    protected $accessToken;

    /**
     * Uploader constructor.
     *
     * @param string $clientId      The appkey from youku.com
     * @param string $accessToken   The access token from youku.com
     */
    public function __construct(string $clientId, string $accessToken)
    {
        $this->clientId = $clientId;
        $this->accessToken = $accessToken;

        $this->api = new Api(new Client());
    }

    /**
     * Upload video.
     *
     * @param string $file      The path of file.
     * @param array  $meta
     * @param array  $configure
     * @return string
     * @throws UploadException
     */
    public function upload(string $file, array $meta = [], array $configure = []): string
    {
        $interface = $this->createUploadInterface(
            $file,
            $meta['title'] ?? basename($file),
            $meta['tags'] ?? [],
            $meta['description'] ?? '',
            $meta['category'] ?? 'Other',
            $meta['thumbnail'] ?? null,
            $meta['copyrightType'] ?? 'original',
            $meta['publicType'] ?? 'all',
            $meta['watchPassword'] ?? null,
            $configure['oss'] ?? 0,
            $meta['deshake'] ?? 0
        );

        $commit = $configure === 0
            ? $this->uploadInOriginalMethod($file, $interface, $configure)
            : $this->uploadInOssMethod($file, $interface);

        return $commit->getVideoId() ?? '';
    }

    protected function uploadInOriginalMethod(string $file, Create $interface, array $configure): Commit
    {
        $this->createFile(
            $file,
            $interface->getUploadToken(),
            $ip = gethostbyname($interface->getUploadServerUri()),
            $configure['sliceLength'] ?? 10485760
        );

        $slices = $this->sliceBinary($file, $configure['sliceLength'] * 1024);
        $this->uploadSlices($slices, $interface->getUploadToken(), $ip, $configure['sliceLength'] * 1024);

        do {
            $check = $this->checkUploaded($interface->getUploadToken(), $ip);

            if ($check->getStatus() === 2 || $check->getStatus() === 3) {
                sleep($configure['checkWaiting'] ?? 60);
            }
        } while (!$check->isFinished() || $check->getStatus() !== 1);

        return $this->api
            ->commit($this->accessToken, $this->clientId, $interface->getUploadToken(), $check->getUploadServerIp());
    }

    protected function uploadInOssMethod(string $file, Create $interface): Commit
    {
        try {
            $ossClient = new OssClient($interface->getTempAccessId(), $interface->getTempAccessSecret(), $interface->getEndpoint());
            $ossClient->uploadFile($interface->getOssBucket(), $interface->getOssObject(), $file);

            return $this->api
                ->commit($this->accessToken, $this->clientId, $interface->getUploadToken());
        } catch (OssException $exception) {
            throw new UploadException(new Error($exception->getCode(), 'Aliyun Oss Exception', $exception->getMessage()), $exception);
        }
    }

    protected function sliceBinary(string $file, int $chunkSize): array
    {
        $file = fopen($file, 'rb');
        $slices = [];
        $i = 0;
        while ($data = stream_get_contents($file, $chunkSize, $chunkSize * $i++)) {
            $slices[] = $data;
        }

        fclose($file);

        return $slices ?? [];
    }

    protected function uploadSlices(array $slices, string $uploadToken, string $ip, int $chunkSize)
    {
        $task = $this->createSliceRoot($uploadToken, $ip)->getSliceTaskId();
        $i = 0;

        foreach ($slices as $slice) {
            $this->uploadCurrentSlice(
                $slice,
                $uploadToken,
                $task++,
                $chunkSize * $i++,
                $ip
            );
        }
    }

    protected function createUploadInterface(
        string $file,
        string $title,
        array $tags,
        string $description,
        string $category = 'Other',
        ?string $thumbnail = null,
        string $copyrightType = 'original',
        string $publicType = 'all',
        ?string $watchPassword = null,
        int $isNew = 0,
        int $deshake = 0
    ): Create {
        return $this->api->create(
            $this->clientId,
            $this->accessToken,
            $title,
            implode(',', $tags),
            $description,
            basename($file),
            md5_file($file),
            filesize($file),
            $category,
            $thumbnail,
            $copyrightType,
            $publicType,
            $watchPassword,
            0,
            $isNew,
            $deshake
        );
    }

    protected function createFile(string $file, string $uploadToken, string $ip, int $sliceLength = 5210)
    {
        $this->api->createFile(
            $ip,
            $uploadToken,
            filesize($file),
            pathinfo($file, PATHINFO_EXTENSION),
            $sliceLength
        );
    }

    protected function createSliceRoot(string $uploadToken, string $ip): NewSlice
    {
        return $this->api->newSlice(
            $ip,
            $uploadToken
        );
    }

    protected function uploadCurrentSlice(string $binary, string $uploadToken, string $sliceTaskId, int $offset, string $ip): UploadSlice
    {
        return $this->api->uploadSlice(
            $ip,
            $uploadToken,
            $sliceTaskId,
            $offset,
            strlen($binary),
            $binary,
            dechex(crc32($binary)),
            bin2hex(md5($binary, true))
        );
    }

    protected function checkUploaded(string $uploadToken, string $ip): Check
    {
        return $this->api->check($ip, $uploadToken);
    }
}
