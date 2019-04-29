<?php

namespace Tests\Api\Response;

use Chivincent\Youku\Api\Response\Commit;
use Chivincent\Youku\Exception\UploadException;
use PHPUnit\Framework\TestCase;

class CommitTest extends TestCase
{
    public function testJson()
    {
        $commit = Commit::json(json_encode([
            'video_id' => 'XMjg1MTcyNDQ0',
        ]));

        $this->assertInstanceOf(Commit::class, $commit);
        $this->assertSame('XMjg1MTcyNDQ0', $commit->getVideoId());
    }

    public function testJsonBuildFailed()
    {
        $commit = Commit::json(json_encode([
            'undefined_key' => 'undefined_value'
        ]));

        $this->assertNull($commit);
    }

    public function testJsonHasError()
    {
        $this->expectException(UploadException::class);
        $this->expectExceptionCode(1001);
        $this->expectExceptionMessage('System Exception: Service temporarily unavailable');
        Commit::json(json_encode([
            'error' => [
                'code' => 1001,
                'type' => 'System Exception',
                'description' => 'Service temporarily unavailable',
            ]
        ]));
    }

    public function testConstruct()
    {
        $commit = new Commit('XMjg1MTcyNDQ0');

        $this->assertInstanceOf(Commit::class, $commit);
        $this->assertSame('XMjg1MTcyNDQ0', $commit->getVideoId());
    }
}
