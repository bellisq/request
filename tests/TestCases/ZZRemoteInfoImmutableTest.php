<?php

namespace Bellisq\Request\Tests\TestCases;

use PHPUnit\Framework\TestCase;
use Bellisq\Request\Containers\HttpRemoteInfoMutable;
use Bellisq\Request\Containers\HttpRemoteInfoImmutable;
use Bellisq\Request\Containers\RequestDataContainer;


class ZZRemoteInfoImmutableTest
    extends TestCase
{
    public function testConstruction()
    {
        $rdc = new RequestDataContainer;
        new HttpRemoteInfoMutable([
            'REMOTE_ADDR' => '33.4.33.4',
            'REMOTE_PORT' => '80'
        ], $rdc);

        $rim = new HttpRemoteInfoImmutable($rdc);

        $this->assertTrue('33.4.33.4' === $rim->address);
        $this->assertTrue(80 === $rim->port);
    }
}