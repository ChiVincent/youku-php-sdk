<?php

namespace Tests\Api\Response;

use Chivincent\Youku\Api\Response\Cancel;
use PHPUnit\Framework\TestCase;

class CancelTest extends TestCase
{
    public function testJson()
    {
        $cancel = Cancel::json(json_encode([
            'upload_token' => '1a2b3c4d',
        ]));

        $this->assertInstanceOf(Cancel::class, $cancel);
        $this->assertSame('1a2b3c4d', $cancel->getUploadToken());
    }

    public function testJsonBuildFailed()
    {
        $cancel = Cancel::json(json_encode([
            'undefined_key' => 'undefined_value',
        ]));

        $this->assertNull($cancel);
    }

    public function testConstruct()
    {
        $cancel = new Cancel('1a2b3c4d');

        $this->assertInstanceOf(Cancel::class, $cancel);
        $this->assertSame('1a2b3c4d', $cancel->getUploadToken());
    }

    public function testGetUploadToken()
    {
        $cancel = new Cancel('1a2b3c4d');

        $this->assertSame('1a2b3c4d', $cancel->getUploadToken());
    }
}
