<?php

namespace Bellisq\Request\Tests\TestCases;

use Bellisq\Request\Containers\HttpRequestLineImmutable;
use Bellisq\Request\Containers\HttpRequestLineMutable;
use Bellisq\Request\Containers\RequestDataContainer;
use PHPUnit\Framework\TestCase;
use Strict\Property\Errors\ReadonlyPropertyError;


class ZZRequestLineImmutableTest
    extends TestCase
{
    /** @var HttpRequestLineImmutable */
    private $hrlm;

    public function setUp()
    {
        $rdc = new RequestDataContainer;
        new HttpRequestLineMutable([
            'HTTP_HOST'       => 'example.com',
            'REQUEST_URI'     => '/index?p=1024&q=2048',
            'QUERY_STRING'    => 'p=1024&q=2048',
            'REQUEST_METHOD'  => 'GET',
            'SERVER_PROTOCOL' => 'HTTP/1.0'
        ], $rdc);
        $this->hrlm = new HttpRequestLineImmutable($rdc);
    }

    public function testWrite()
    {
        $this->expectException(ReadonlyPropertyError::class);
        $this->hrlm->port = 334;
    }

    public function testUri()
    {
        $this->assertEquals('http://example.com/index?p=1024&q=2048', $this->hrlm->uri);
    }
}