<?php

namespace Bellisq\Request\Tests\TestCases;

use Bellisq\Request\Containers\HttpParsedVariablesMutable;
use Bellisq\Request\Containers\HttpRemoteInfoMutable;
use Bellisq\Request\Containers\HttpRequestHeaderMutable;
use Bellisq\Request\Containers\HttpRequestLineMutable;
use Bellisq\Request\Containers\RequestDataContainer;
use Bellisq\Request\RequestMutable;
use PHPUnit\Framework\TestCase;


class ZZRequestMutableTest
    extends TestCase
{
    public function testBehavior()
    {
        $rm = new RequestMutable(
            [
                'REMOTE_ADDR' => '33.4.33.4',
                'REMOTE_PORT' => '44444'
            ], [], [], [], []
        );

        $this->assertInstanceOf(HttpRequestLineMutable::class, $rm->line);
        $this->assertInstanceOf(HttpRequestHeaderMutable::class, $rm->header);
        $this->assertInstanceOf(HttpRemoteInfoMutable::class, $rm->remote);
        $this->assertInstanceOf(HttpParsedVariablesMutable::class, $rm->parsed);

        $rm->line->port = 334;
        $this->assertEquals(334, $rm->line->port);

        $rs = clone $rm;

        $rs->line->port = 443;

        $this->assertEquals(334, $rm->line->port);
        $this->assertEquals(443, $rs->line->port);
    }
}