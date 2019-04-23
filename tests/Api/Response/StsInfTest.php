<?php

namespace Tests\Api\Response;

use Chivincent\Youku\Api\Response\StsInf;
use PHPUnit\Framework\TestCase;

class StsInfTest extends TestCase
{
    public function testJson()
    {
        $stsInf = StsInf::json(json_encode([
            'upload_token' => '1a2b3c4d',
            'endpoint' => 'oss-cn-shanghai.aliyuncs.com',
            'security_token' => 'fake-security-token',
            'temp_access_id' => 'fake-temp-access-id',
            'temp_access_secret' => 'fake-temp-access-secret',
            'expire_time' => '2019-01-07T13:03:04Z',
        ]));

        $this->assertInstanceOf(StsInf::class, $stsInf);
        $this->assertSame('1a2b3c4d', $stsInf->getUploadToken());
        $this->assertSame('oss-cn-shanghai.aliyuncs.com', $stsInf->getEndpoint());
        $this->assertSame('fake-security-token', $stsInf->getSecurityToken());
        $this->assertSame('fake-temp-access-id', $stsInf->getTempAccessId());
        $this->assertSame('fake-temp-access-secret', $stsInf->getTempAccessSecret());
        $this->assertSame('2019-01-07T13:03:04Z', $stsInf->getExpireTime());
    }

    public function testJsonBuildFailed()
    {
        $stsInf = StsInf::json(json_encode([
            'undefined_key' => 'undefined_value',
        ]));

        $this->assertNull($stsInf);
    }

    public function testConstruct()
    {
        $stsInf = new StsInf('1a2b3c4d', 'oss-cn-shanghai.aliyuncs.com', 'fake-security-token', 'fake-temp-access-id', 'fake-temp-access-secret', '2019-01-07T13:03:04Z');

        $this->assertInstanceOf(StsInf::class, $stsInf);
        $this->assertSame('1a2b3c4d', $stsInf->getUploadToken());
        $this->assertSame('oss-cn-shanghai.aliyuncs.com', $stsInf->getEndpoint());
        $this->assertSame('fake-security-token', $stsInf->getSecurityToken());
        $this->assertSame('fake-temp-access-id', $stsInf->getTempAccessId());
        $this->assertSame('fake-temp-access-secret', $stsInf->getTempAccessSecret());
        $this->assertSame('2019-01-07T13:03:04Z', $stsInf->getExpireTime());
    }
}