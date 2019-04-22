<?php

namespace Tests\Api\Response;

use Chivincent\Youku\Api\Response\Create;
use PHPUnit\Framework\TestCase;

class CreateTest extends TestCase
{
    public function testJson()
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

    public function testJsonBuildFailed()
    {
        $create = Create::json(json_encode([
            'undefined_key' => 'undefined_value',
        ]));

        $this->assertNull($create);
    }

    public function testConstruct()
    {
        $create = new Create('1a2b3c4d', 'xxxxxx', 'g01.upload.youku.com');

        $this->assertInstanceOf(Create::class, $create);
        $this->assertSame('1a2b3c4d', $create->getUploadToken());
        $this->assertSame('xxxxxx', $create->getVideoId());
        $this->assertSame('g01.upload.youku.com', $create->getUploadServerUri());
    }
}
