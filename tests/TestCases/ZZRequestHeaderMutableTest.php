<?php

namespace Bellisq\Request\Tests\TestCases;

use Bellisq\Request\Containers\HttpRequestHeaderMutable;
use Bellisq\Request\Containers\RequestDataContainer;
use PHPUnit\Framework\TestCase;
use Bellisq\Request\Containers\MutableArray;


class ZZRequestHeaderMutableTest
    extends TestCase
{
    public function testConstructionFull()
    {
        $hrhm = new HttpRequestHeaderMutable([
            'HTTP_USER_AGENT'        => '33-4 User Agent',
            'HTTP_ACCEPT_LANGUAGE'   => 'ja,en-US;q=0.9,en;q=0.8',
            'HTTP_IF_MODIFIED_SINCE' => 'Fri Jan 01 2010 00:00:00 GMT',
            'HTTP_IF_NONE_MATCH'     => '"3342933429"',
            'HTTP_REFERER'           => 'https://example.com'
        ], new RequestDataContainer());

        $this->assertEquals('33-4 User Agent', $hrhm->userAgent);
        $this->assertEquals([
            'ja', 'en-US', 'en'
        ], $hrhm->acceptLanguage->getArrayCopy());
        $this->assertEquals(1262304000, $hrhm->ifModifiedSince);
        $this->assertEquals('"3342933429"', $hrhm->ifNoneMatch);
        $this->assertEquals('https://example.com', $hrhm->referer);
    }

    public function testConstructionNone()
    {
        $hrhm = new HttpRequestHeaderMutable([], new RequestDataContainer());
        $this->assertNull($hrhm->userAgent);
        $this->assertEquals(['en'], $hrhm->acceptLanguage->getArrayCopy());
        $this->assertNull($hrhm->ifModifiedSince);
        $this->assertNull($hrhm->ifNoneMatch);
        $this->assertNull($hrhm->referer);
    }

    public function testWrite()
    {
        $hrhm = new HttpRequestHeaderMutable([], new RequestDataContainer());
        $hrhm->userAgent = '33-4 User Agent';
        $hrhm->acceptLanguage = ['ja', 'en-US', 'en'];
        $hrhm->ifModifiedSince = 1262304000;
        $hrhm->ifNoneMatch = null;
        $hrhm->referer = 'https://example.com';

        $this->assertEquals('33-4 User Agent', $hrhm->userAgent);
        $this->assertEquals([
            'ja', 'en-US', 'en'
        ], $hrhm->acceptLanguage->getArrayCopy());
        $this->assertEquals(1262304000, $hrhm->ifModifiedSince);
        $this->assertNull($hrhm->ifNoneMatch);
        $this->assertEquals('https://example.com', $hrhm->referer);

        $hrhm->acceptLanguage = new MutableArray(['en', 'en-US', 'ja']);
        $this->assertEquals([
            'en', 'en-US', 'ja'
        ], $hrhm->acceptLanguage->getArrayCopy());
    }
}