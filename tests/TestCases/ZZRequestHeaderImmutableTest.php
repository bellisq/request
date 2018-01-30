<?php

namespace Bellisq\Request\Tests\TestCases;

use Bellisq\Request\Containers\HttpRequestHeaderImmutable;
use Bellisq\Request\Containers\HttpRequestHeaderMutable;
use Bellisq\Request\Containers\RequestDataContainer;
use PHPUnit\Framework\TestCase;


class ZZRequestHeaderImmutableTest
    extends TestCase
{
    public function testBehavior()
    {
        $rdc = new RequestDataContainer;
        new HttpRequestHeaderMutable([], $rdc);
        $hrhm = new HttpRequestHeaderImmutable($rdc);


        $this->assertNull($hrhm->userAgent);
        $this->assertEquals(['en'], $hrhm->acceptLanguage);
        $this->assertNull($hrhm->ifModifiedSince);
        $this->assertNull($hrhm->ifNoneMatch);
        $this->assertNull($hrhm->referer);
    }
}