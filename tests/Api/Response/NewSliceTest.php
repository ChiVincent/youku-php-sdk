<?php

namespace Tests\Api\Response;

use Chivincent\Youku\Api\Response\NewSlice;
use Chivincent\Youku\Exception\UploadException;
use PHPUnit\Framework\TestCase;

class NewSliceTest extends TestCase
{
    public function testJson()
    {
        $newSlice = NewSlice::json(json_encode([
            'slice_task_id' => 1328793281567,
            'offset' => 12358023,
            'length' => 12345,
            'transferred' => 12358023,
            'finished' => false,
        ]));

        $this->assertInstanceOf(NewSlice::class, $newSlice);
        $this->assertSame(1328793281567, $newSlice->getSliceTaskId());
        $this->assertSame(12358023, $newSlice->getOffset());
        $this->assertSame(12345, $newSlice->getLength());
        $this->assertSame(12358023, $newSlice->getTransferred());
        $this->assertFalse($newSlice->isFinished());
    }

    public function testJsonBuildFailed()
    {
        $newSlice = NewSlice::json(json_encode([
            'undefined_key' => 'undefined_value',
        ]));

        $this->assertNull($newSlice);
    }

    public function testJsonHasError()
    {
        $this->expectException(UploadException::class);
        $this->expectExceptionCode(1001);
        $this->expectExceptionMessage('System Exception: Service temporarily unavailable');
        NewSlice::json(json_encode([
            'error' => [
                'code' => 1001,
                'type' => 'System Exception',
                'description' => 'Service temporarily unavailable',
            ]
        ]));
    }

    public function testConstruct()
    {
        $newSlice = new NewSlice(1328793281567, 12358023, 12345, 12358023, false);

        $this->assertInstanceOf(NewSlice::class, $newSlice);
        $this->assertSame(1328793281567, $newSlice->getSliceTaskId());
        $this->assertSame(12358023, $newSlice->getOffset());
        $this->assertSame(12345, $newSlice->getLength());
        $this->assertSame(12358023, $newSlice->getTransferred());
        $this->assertFalse($newSlice->isFinished());
    }
}
