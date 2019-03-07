<?php

namespace Tests\Api\Response;

use Chivincent\Youku\Api\Response\Error;
use PHPUnit\Framework\TestCase;

class ErrorTest extends TestCase
{
    public function testJson()
    {
        $error = Error::json(json_encode([
            'error' => [
                'code' => 1005,
                'type' => 'SystemException',
                'description' => 'Client id invalid',
            ]
        ]));

        $this->assertInstanceOf(Error::class, $error);
        $this->assertSame(1005, $error->getCode());
        $this->assertSame('SystemException', $error->getType());
        $this->assertSame('Client id invalid', $error->getDescription());
    }

    public function testJsonBuildFailed()
    {
        $error = Error::json(json_encode([
            'undefined_key' => 'undefined_value',
        ]));

        $this->assertNull($error);
    }

    public function testConstruct()
    {
        $error = new Error(1005, 'SystemException', 'Client id invalid');

        $this->assertInstanceOf(Error::class, $error);
        $this->assertSame(1005, $error->getCode());
        $this->assertSame('SystemException', $error->getType());
        $this->assertSame('Client id invalid', $error->getDescription());
    }
}
