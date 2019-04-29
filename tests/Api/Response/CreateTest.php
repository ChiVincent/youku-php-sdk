<?php

namespace Tests\Api\Response;

use Chivincent\Youku\Api\Response\Create;
use Chivincent\Youku\Exception\UploadException;
use PHPUnit\Framework\TestCase;

class CreateTest extends TestCase
{
    public function testJsonInOriginalMethod()
    {
        $create = Create::json(json_encode([
            'upload_token' => '1a2b3c4d',
            'video_id' => 'xxxxxx',
            'upload_server_uri' => 'g01.upload.youku.com',
        ]));

        $this->assertInstanceOf(Create::class, $create);
        $this->assertSame('1a2b3c4d', $create->getUploadToken());
        $this->assertSame('xxxxxx', $create->getVideoId());
        $this->assertSame('g01.upload.youku.com', $create->getUploadServerUri());
    }

    public function testJsonInOssMethod()
    {
        $create = Create::json(json_encode([
            'upload_token' => '1a2b3c4d',
            'video_id' => 'fake-video-id',
            'endpoint' => 'oss-cn-shanghai.aliyuncs.com',
            'security_token' => 'fake-security-token',
            'oss_bucket' => 'yk-source-upload',
            'oss_object' => 'fake-oss-object',
            'temp_access_id' => 'fake-temp-access-id',
            'temp_access_secret' => 'fake-temp-access-secret',
            'expire_time' => '2019-01-07T13:03:04Z',
        ]));

        $this->assertInstanceOf(Create::class, $create);
        $this->assertSame('1a2b3c4d', $create->getUploadToken());
        $this->assertSame('fake-video-id', $create->getVideoId());
        $this->assertSame('oss-cn-shanghai.aliyuncs.com', $create->getEndpoint());
        $this->assertSame('fake-security-token', $create->getSecurityToken());
        $this->assertSame('yk-source-upload', $create->getOssBucket());
        $this->assertSame('fake-oss-object', $create->getOssObject());
        $this->assertSame('fake-temp-access-id', $create->getTempAccessId());
        $this->assertSame('fake-temp-access-secret', $create->getTempAccessSecret());
        $this->assertSame('2019-01-07T13:03:04Z', $create->getExpireTime());
    }

    public function testJsonBuildFailed()
    {
        $create = Create::json(json_encode([
            'undefined_key' => 'undefined_value',
        ]));

        $this->assertNull($create);
    }

    public function testJsonHasError()
    {
        $this->expectException(UploadException::class);
        $this->expectExceptionCode(1001);
        $this->expectExceptionMessage('System Exception: Service temporarily unavailable');
        Create::json(json_encode([
            'error' => [
                'code' => 1001,
                'type' => 'System Exception',
                'description' => 'Service temporarily unavailable',
            ]
        ]));
    }

    public function testConstructInOriginalMethod()
    {
        $create = new Create('1a2b3c4d', 'xxxxxx', 'g01.upload.youku.com');

        $this->assertInstanceOf(Create::class, $create);
        $this->assertSame('1a2b3c4d', $create->getUploadToken());
        $this->assertSame('xxxxxx', $create->getVideoId());
        $this->assertSame('g01.upload.youku.com', $create->getUploadServerUri());
    }

    public function testConstructInOssMethod()
    {
        $create = new Create(
            '1a2b3c4d',
            'fake-video-id',
            null,
            'oss-cn-shanghai.aliyuncs.com',
            'fake-security-token',
            'yk-source-upload',
            'fake-oss-object',
            'fake-temp-access-id',
            'fake-temp-access-secret',
            '2019-01-07T13:03:04Z'
        );

        $this->assertInstanceOf(Create::class, $create);
        $this->assertSame('1a2b3c4d', $create->getUploadToken());
        $this->assertSame('fake-video-id', $create->getVideoId());
        $this->assertSame('oss-cn-shanghai.aliyuncs.com', $create->getEndpoint());
        $this->assertSame('fake-security-token', $create->getSecurityToken());
        $this->assertSame('yk-source-upload', $create->getOssBucket());
        $this->assertSame('fake-oss-object', $create->getOssObject());
        $this->assertSame('fake-temp-access-id', $create->getTempAccessId());
        $this->assertSame('fake-temp-access-secret', $create->getTempAccessSecret());
        $this->assertSame('2019-01-07T13:03:04Z', $create->getExpireTime());
    }
}
