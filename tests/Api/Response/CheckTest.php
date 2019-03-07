<?php

namespace Tests\Api\Response;

use Chivincent\Youku\Api\Response\Check;
use PHPUnit\Framework\TestCase;

class CheckTest extends TestCase
{
    public function testJson()
    {
        $check = Check::json(json_encode([
            'status' => 4,
            'upload_server_ip' => '16.103.65.55',
            'transferred_percent' => 0,
            'confirmed_percent' => 0,
            'empty_tasks' => 114,
            'finished' => false,
        ]));

        $this->assertInstanceOf(Check::class, $check);
        $this->assertSame(4, $check->getStatus());
        $this->assertSame('16.103.65.55', $check->getUploadServerIp());
        $this->assertSame(0, $check->getTransferredPercent());
        $this->assertSame(0, $check->getConfirmedPercent());
        $this->assertSame(114, $check->getEmptyTasks());
        $this->assertSame(false, $check->isFinished());
    }

    public function testJsonBuildFailed()
    {
        $check = Check::json(json_encode([
            'undefined_key' => 'undefined_value',
        ]));

        $this->assertNull($check);
    }

    public function testConstructor()
    {
        $check = new Check(4, 0, 0, 114, false, '16.103.65.55');

        $this->assertInstanceOf(Check::class, $check);
        $this->assertSame(4, $check->getStatus());
        $this->assertSame('16.103.65.55', $check->getUploadServerIp());
        $this->assertSame(0, $check->getTransferredPercent());
        $this->assertSame(0, $check->getConfirmedPercent());
        $this->assertSame(114, $check->getEmptyTasks());
        $this->assertSame(false, $check->isFinished());
    }

    public function testConstructorByOptionalValues()
    {
        $check = new Check(4, null, null, null, false, '16.103.65.55');

        $this->assertInstanceOf(Check::class, $check);
        $this->assertSame(4, $check->getStatus());
        $this->assertSame('16.103.65.55', $check->getUploadServerIp());
        $this->assertSame(null, $check->getTransferredPercent());
        $this->assertSame(null, $check->getConfirmedPercent());
        $this->assertSame(null, $check->getEmptyTasks());
        $this->assertSame(false, $check->isFinished());
    }
}
