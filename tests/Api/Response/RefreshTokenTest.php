<?php

namespace Tests\Api\Response;

use Chivincent\Youku\Api\Response\RefreshToken;
use PHPUnit\Framework\TestCase;

class RefreshTokenTest extends TestCase
{
    public function testJson()
    {
        $refreshToken = RefreshToken::json(json_encode([
            'access_token' => '11d0b7627154f0dd000e6084f3811598',
            'expires_in' => 3600,
            'refresh_token' => '4bda296b570a6bba6ff02944cf10d13f',
            'token_type' => 'bearer',
        ]));

        $this->assertInstanceOf(RefreshToken::class, $refreshToken);
        $this->assertSame('11d0b7627154f0dd000e6084f3811598', $refreshToken->getAccessToken());
        $this->assertSame(3600, $refreshToken->getExpiresIn());
        $this->assertSame('4bda296b570a6bba6ff02944cf10d13f', $refreshToken->getRefreshToken());
        $this->assertSame('bearer', $refreshToken->getTokenType());
    }

    public function testJsonBuildFailed()
    {
        $refreshToken = RefreshToken::json(json_encode([
            'undefined_key' => 'undefined_value',
        ]));

        $this->assertNull($refreshToken);
    }

    public function testConstruct()
    {
        $refreshToken = new RefreshToken('11d0b7627154f0dd000e6084f3811598', 3600, '4bda296b570a6bba6ff02944cf10d13f', 'bearer');

        $this->assertInstanceOf(RefreshToken::class, $refreshToken);
        $this->assertSame('11d0b7627154f0dd000e6084f3811598', $refreshToken->getAccessToken());
        $this->assertSame(3600, $refreshToken->getExpiresIn());
        $this->assertSame('4bda296b570a6bba6ff02944cf10d13f', $refreshToken->getRefreshToken());
        $this->assertSame('bearer', $refreshToken->getTokenType());
    }
}
