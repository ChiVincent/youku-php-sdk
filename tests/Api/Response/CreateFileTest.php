<?php

namespace Tests\Api\Response;

use Chivincent\Youku\Api\Response\CreateFile;
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

    public function testConstruct()
    {
        $createFile = new CreateFile();

        $this->assertInstanceOf(CreateFile::class, $createFile);
    }
}
