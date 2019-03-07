<?php

namespace Tests\Api\Response;

use Chivincent\Youku\Api\Response\UploadSlice;
use PHPUnit\Framework\TestCase;

class UploadSliceTest extends TestCase
{
    public function testJson()
    {
        $uploadSlice = UploadSlice::json(json_encode([
            'slice_task_id' => 1328793281567,
            'offset' => 12358023,
            'length' => 12345,
            'transferred' => 12358023,
            'finished' => false,
        ]));

        $this->assertInstanceOf(UploadSlice::class, $uploadSlice);
        $this->assertSame(1328793281567, $uploadSlice->getSliceTaskId());
        $this->assertSame(12358023, $uploadSlice->getOffset());
        $this->assertSame(12345, $uploadSlice->getLength());
        $this->assertSame(12358023, $uploadSlice->getOffset());
        $this->assertFalse($uploadSlice->isFinished());
    }

    public function testJsonBuildFailed()
    {
        $uploadSlice = UploadSlice::json(json_encode([
            'undefined_key' => 'undefined_value',
        ]));

        $this->assertNull($uploadSlice);
    }

    public function testConstruct()
    {
        $uploadSlice = new UploadSlice(1328793281567, 12358023, 12345, 12358023, false);

        $this->assertInstanceOf(UploadSlice::class, $uploadSlice);
        $this->assertSame(1328793281567, $uploadSlice->getSliceTaskId());
        $this->assertSame(12358023, $uploadSlice->getOffset());
        $this->assertSame(12345, $uploadSlice->getLength());
        $this->assertSame(12358023, $uploadSlice->getOffset());
        $this->assertFalse($uploadSlice->isFinished());
    }
}
