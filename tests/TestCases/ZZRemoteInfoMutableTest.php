<?php

namespace Bellisq\Request\Tests\TestCases;

use Bellisq\Request\Containers\HttpRemoteInfoMutable;
use Bellisq\Request\Containers\RequestDataContainer;
use PHPUnit\Framework\TestCase;


class ZZRemoteInfoMutableTest
    extends TestCase
{
    public function testConstruction()
    {
        $rim = new HttpRemoteInfoMutable([
            'REMOTE_ADDR' => '33.4.33.4',
            'REMOTE_PORT' => '80'
        ], new RequestDataContainer);

        $this->assertTrue('33.4.33.4' === $rim->address);
        $this->assertTrue(80 === $rim->port);

        $rim->address = '29.29.29.29';
        $rim->port = 334;

        $this->assertTrue('29.29.29.29' === $rim->address);
        $this->assertTrue(334 === $rim->port);
    }
}