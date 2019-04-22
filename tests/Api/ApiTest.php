<?php

namespace Tests\Api;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use Chivincent\Youku\Api\Api;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Handler\MockHandler;
use Chivincent\Youku\Api\Response\Check;
use Chivincent\Youku\Api\Response\Cancel;
use Chivincent\Youku\Api\Response\Commit;
use Chivincent\Youku\Api\Response\Create;
use Chivincent\Youku\Api\Response\NewSlice;
use Chivincent\Youku\Api\Response\CreateFile;
use Chivincent\Youku\Api\Response\UploadSlice;
use Chivincent\Youku\Exception\UploadException;
use Chivincent\Youku\Api\Response\RefreshToken;

class ApiTest extends TestCase
{
    public function testRefreshToken()
    {
        $mock = new MockHandler([
            new Response(200, [], json_encode([
                'access_token' => '11d0b7627154f0dd000e6084f3811598',
                'expires_in' => '3600',
                'refresh_token' => '4bda296b570a6bba6ff02944cf10d13f',
                'token_type' => 'bearer',
            ]))
        ]);

        $api = new Api(new Client(['handler' => HandlerStack::create($mock)]));
        $response = $api->refreshToken('123', 'refresh_token', '4bda296b570a6bba6ff02944cf10d13f');

        $this->assertInstanceOf(RefreshToken::class, $response);
        $this->assertSame('11d0b7627154f0dd000e6084f3811598', $response->getAccessToken());
        $this->assertSame(3600, $response->getExpiresIn());
        $this->assertSame('4bda296b570a6bba6ff02944cf10d13f', $response->getRefreshToken());
        $this->assertSame('bearer', $response->getTokenType());
    }

    public function testCreate()
    {
        $mock = new MockHandler([
            new Response(201, [], json_encode([
                'upload_token' => '1a2b3c4d',
                'video_id' => 'xxxxxx',
                'upload_server_uri' => 'g01.upload.youku.com',
            ])),
        ]);

        $api = new Api(new Client(['handler' => HandlerStack::create($mock)]));
        $response = $api->create(
            '123',
            'demo_access_token',
            'demo video',
            'demo',
            'this is a demo',
            'demo.avi',
            '00000000000000000000000000',
            '123456',
            null,
            'original',
            'password',
            '123456'
        );

        $this->assertInstanceOf(Create::class, $response);
        $this->assertSame('1a2b3c4d', $response->getUploadToken());
        $this->assertSame('xxxxxx', $response->getVideoId());
        $this->assertSame('g01.upload.youku.com', $response->getUploadServerUri());
    }

    public function testCreateFile()
    {
        $mock = new MockHandler([
            new Response(201, [], '{}'),
        ]);

        $api = new Api(new Client(['handler' => HandlerStack::create($mock)]));
        $response = $api->createFile('1.1.1.1', '1a2b3c4d', '87654321', 'avi', 10485760);

        $this->assertInstanceOf(CreateFile::class, $response);
    }

    public function testNewSlice()
    {
        $mock = new MockHandler([
            new Response(201, [], json_encode([
                'slice_task_id' => 1328793281567,
                'offset' => 12358023,
                'length' => 12345,
                'transferred' => 12358023,
                'finished' => false,
            ])),
        ]);

        $api = new Api(new Client(['handler' => HandlerStack::create($mock)]));
        $response = $api->newSlice('1.1.1.1', '1a2b3c4d');

        $this->assertInstanceOf(NewSlice::class, $response);
        $this->assertSame(1328793281567, $response->getSliceTaskId());
        $this->assertSame(12358023, $response->getOffset());
        $this->assertSame(12345, $response->getLength());
        $this->assertSame(12358023, $response->getTransferred());
        $this->assertFalse($response->isFinished());
    }

    public function testUploadSlice()
    {
        $mock = new MockHandler([
            new Response(201, [], json_encode([
                'slice_task_id' => 1328793281567,
                'offset' => 12358023,
                'length' => 12345,
                'transferred' => 12358023,
                'finished' => false,
            ])),
        ]);

        $api = new Api(new Client(['handler' => HandlerStack::create($mock)]));
        $response = $api->uploadSlice('1.1.1.1', '1a2b3c4d', 1328793281567, 12358023, 12345, 12358023);

        $this->assertInstanceOf(UploadSlice::class, $response);
        $this->assertSame(1328793281567, $response->getSliceTaskId());
        $this->assertSame(12358023, $response->getOffset());
        $this->assertSame(12345, $response->getLength());
        $this->assertSame(12358023, $response->getTransferred());
        $this->assertFalse($response->isFinished());
    }

    public function testCheck()
    {
        $mock = new MockHandler([
            new Response(200, [], json_encode([
                'status' => 4,
                'upload_server_ip' => '16.103.65.55',
                'transferred_percent' => 0,
                'confirmed_percent' => 0,
                'empty_tasks' => 114,
                'finished' => false,
            ])),
        ]);

        $api = new Api(new Client(['handler' => HandlerStack::create($mock)]));
        $response = $api->check('1.1.1.1', '1a2b3c4d');

        $this->assertInstanceOf(Check::class, $response);
        $this->assertSame(4, $response->getStatus());
        $this->assertSame('16.103.65.55', $response->getUploadServerIp());
        $this->assertSame(0, $response->getTransferredPercent());
        $this->assertSame(0, $response->getConfirmedPercent());
        $this->assertSame(114, $response->getEmptyTasks());
        $this->assertFalse($response->isFinished());
    }

    public function testCommit()
    {
        $mock = new MockHandler([
            new Response(200, [], json_encode([
                'video_id' => 'XMjg1MTcyNDQ0',
            ])),
        ]);

        $api = new Api(new Client(['handler' => HandlerStack::create($mock)]));
        $response = $api->commit('demo_access_token', '123', '1a2b3c4d', '1.2.3.4');

        $this->assertInstanceOf(Commit::class, $response);
        $this->assertSame('XMjg1MTcyNDQ0', $response->getVideoId());
    }

    public function testCancel()
    {
        $mock = new MockHandler([
            new Response(200, [], json_encode([
                'upload_token' => '1a2b3c4d',
            ])),
        ]);

        $api = new Api(new Client(['handler' => HandlerStack::create($mock)]));
        $response = $api->cancel('demo_access_token', '123', '1a2b3c4d');

        $this->assertInstanceOf(Cancel::class, $response);
        $this->assertSame('1a2b3c4d', $response->getUploadToken());
    }

    public function testErrorOccurred()
    {
        $mock = new MockHandler([
            new Response(400, [], json_encode([
                'error' => [
                    'code' => 1005,
                    'type' => 'SystemException',
                    'description' => 'Client id invalid',
                ],
            ])),
        ]);

        $api = new Api(new Client(['handler' => HandlerStack::create($mock)]));
        $this->expectException(UploadException::class);
        $this->expectExceptionCode(1005);
        $this->expectExceptionMessage('SystemException: Client id invalid');
        $api->refreshToken('123', 'refresh_token', '12345678');
    }
}
