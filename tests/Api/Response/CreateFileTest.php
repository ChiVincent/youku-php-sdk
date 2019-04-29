<?php

namespace Tests\Api\Response;

use Chivincent\Youku\Api\Response\CreateFile;
use Chivincent\Youku\Exception\UploadException;
use PHPUnit\Framework\TestCase;

class CreateFileTest extends TestCase
{
    public function testJson()
    {
        $createFile = CreateFile::json('{}');

        $this->assertInstanceOf(CreateFile::class, $createFile);
    }

    public function testJsonBuildFailed()
    {
        $createFile = CreateFile::json('this-string-cannot-be-json-decode');

        $this->assertNull($createFile);
    }

    public function testJsonHasError()
    {
        $this->expectException(UploadException::class);
        $this->expectExceptionCode(1001);
        $this->expectExceptionMessage('System Exception: Service temporarily unavailable');
        CreateFile::json(json_encode([
            'error' => [
                'code' => 1001,
                'type' => 'System Exception',
                'description' => 'Service temporarily unavailable',
            ]
        ]));
    }

    public function testConstruct()
    {
        $createFile = new CreateFile();

        $this->assertInstanceOf(CreateFile::class, $createFile);
    }
}
