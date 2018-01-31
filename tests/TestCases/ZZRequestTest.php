<?php

namespace Bellisq\Request\Tests\TestCases;

use Bellisq\Request\Containers\HttpParsedVariablesImmutable;
use Bellisq\Request\Containers\HttpRemoteInfoImmutable;
use Bellisq\Request\Containers\HttpRequestHeaderImmutable;
use Bellisq\Request\Containers\HttpRequestLineImmutable;
use Bellisq\Request\Request;
use Bellisq\Request\RequestMutable;
use PHPUnit\Framework\TestCase;


class ZZRequestTest
    extends TestCase
{
    public function testBehavior()
    {
        $rs = new Request(
            new RequestMutable(
                [
                    'REMOTE_ADDR' => '33.4.33.4',
                    'REMOTE_PORT' => '44444',
                    'SERVER_PORT' => '443',
                ], [], [], [], []
            )
        );

        $this->assertInstanceOf(HttpRequestLineImmutable::class, $rs->line);
        $this->assertInstanceOf(HttpRequestHeaderImmutable::class, $rs->header);
        $this->assertInstanceOf(HttpRemoteInfoImmutable::class, $rs->remote);
        $this->assertInstanceOf(HttpParsedVariablesImmutable::class, $rs->parsed);

        $rm = $rs->getMutable();

        $rm->line->port = 334;

        $this->assertEquals(334, $rm->line->port);
        $this->assertEquals(443, $rs->line->port);
    }
}