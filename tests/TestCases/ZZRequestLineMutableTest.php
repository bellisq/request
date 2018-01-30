<?php

namespace Bellisq\Request\Tests\TestCases;

use Bellisq\Request\Containers\HttpRequestLineMutable;
use Bellisq\Request\Containers\RequestDataContainer;
use DomainException;
use PHPUnit\Framework\TestCase;


class ZZRequestLineMutableTest
    extends TestCase
{
    public function testForwardedConstructionHTTPS()
    {
        $hrlm = new HttpRequestLineMutable([
            'HTTP_X_FORWARDED_PROTO' => 'https',
            'HTTP_HOST'              => 'example.com',
            'SERVER_PORT'            => 400,
            'REQUEST_URI'            => '/index?p=1024&q=2048',
            'QUERY_STRING'           => 'p=1024&q=2048',
            'REQUEST_METHOD'         => 'GET',
            'SERVER_PROTOCOL'        => 'HTTP/1.0'
        ], new RequestDataContainer);

        $this->assertEquals('https', $hrlm->scheme);
        $this->assertEquals('example.com', $hrlm->host);
        $this->assertEquals(443, $hrlm->port);
        $this->assertEquals('/index', $hrlm->path);
        $this->assertEquals('p=1024&q=2048', $hrlm->query);
        $this->assertEquals('GET', $hrlm->method);
        $this->assertEquals('HTTP/1.0', $hrlm->protocol);
        $this->assertEquals('https://example.com/index?p=1024&q=2048', $hrlm->uri);
    }

    public function testForwardedConstructionHTTP()
    {
        $hrlm = new HttpRequestLineMutable([
            'HTTP_X_FORWARDED_PROTO' => 'http',
            'HTTP_HOST'              => 'example.com',
            'SERVER_PORT'            => 400,
            'REQUEST_URI'            => '/index',
            'QUERY_STRING'           => 'p=1024&q=2048',
            'REQUEST_METHOD'         => 'GET',
            'SERVER_PROTOCOL'        => 'HTTP/1.0'
        ], new RequestDataContainer);

        $this->assertEquals('http', $hrlm->scheme);
        $this->assertEquals('example.com', $hrlm->host);
        $this->assertEquals(80, $hrlm->port);
        $this->assertEquals('/index', $hrlm->path);
        $this->assertEquals('p=1024&q=2048', $hrlm->query);
        $this->assertEquals('GET', $hrlm->method);
        $this->assertEquals('HTTP/1.0', $hrlm->protocol);
        $this->assertEquals('http://example.com/index?p=1024&q=2048', $hrlm->uri);
    }

    public function testConstructionHTTPS()
    {
        $hrlm = new HttpRequestLineMutable([
            'HTTPS'           => 'On',
            'HTTP_HOST'       => 'example.com',
            'SERVER_PORT'     => 400,
            'REQUEST_URI'     => '/index',
            'QUERY_STRING'    => '',
            'REQUEST_METHOD'  => 'GET',
            'SERVER_PROTOCOL' => 'HTTP/1.0'
        ], new RequestDataContainer);

        $this->assertEquals('https', $hrlm->scheme);
        $this->assertEquals('example.com', $hrlm->host);
        $this->assertEquals(400, $hrlm->port);
        $this->assertEquals('/index', $hrlm->path);
        $this->assertEquals('', $hrlm->query);
        $this->assertEquals('GET', $hrlm->method);
        $this->assertEquals('HTTP/1.0', $hrlm->protocol);
        $this->assertEquals('https://example.com:400/index', $hrlm->uri);
    }

    public function testConstructionHTTP1()
    {
        $hrlm = new HttpRequestLineMutable([
            'HTTPS'           => 'Off',
            'HTTP_HOST'       => 'example.com',
            'SERVER_PORT'     => 400,
            'REQUEST_URI'     => '/index?p=1024&q=2048',
            'QUERY_STRING'    => 'p=1024&q=2048',
            'REQUEST_METHOD'  => 'GET',
            'SERVER_PROTOCOL' => 'HTTP/1.0'
        ], new RequestDataContainer);

        $this->assertEquals('http', $hrlm->scheme);
        $this->assertEquals('example.com', $hrlm->host);
        $this->assertEquals(400, $hrlm->port);
        $this->assertEquals('/index', $hrlm->path);
        $this->assertEquals('p=1024&q=2048', $hrlm->query);
        $this->assertEquals('GET', $hrlm->method);
        $this->assertEquals('HTTP/1.0', $hrlm->protocol);
        $this->assertEquals('http://example.com:400/index?p=1024&q=2048', $hrlm->uri);
    }

    public function testConstructionHTTP2()
    {
        $hrlm = new HttpRequestLineMutable([
            'HTTP_HOST'       => 'example.com',
            'SERVER_PORT'     => 80,
            'REQUEST_URI'     => '/index?p=1024&q=2048',
            'QUERY_STRING'    => 'p=1024&q=2048',
            'REQUEST_METHOD'  => 'GET',
            'SERVER_PROTOCOL' => 'HTTP/1.0'
        ], new RequestDataContainer);

        $this->assertEquals('http', $hrlm->scheme);
        $this->assertEquals('example.com', $hrlm->host);
        $this->assertEquals(80, $hrlm->port);
        $this->assertEquals('/index', $hrlm->path);
        $this->assertEquals('p=1024&q=2048', $hrlm->query);
        $this->assertEquals('GET', $hrlm->method);
        $this->assertEquals('HTTP/1.0', $hrlm->protocol);
        $this->assertEquals('http://example.com/index?p=1024&q=2048', $hrlm->uri);
    }

    public function testPortCompletionHTTPS()
    {
        $hrlm = new HttpRequestLineMutable([
            'HTTPS'           => 'On',
            'HTTP_HOST'       => 'example.com',
            'REQUEST_URI'     => '/index?p=1024&q=2048',
            'QUERY_STRING'    => 'p=1024&q=2048',
            'REQUEST_METHOD'  => 'GET',
            'SERVER_PROTOCOL' => 'HTTP/1.0'
        ], new RequestDataContainer);

        $this->assertEquals('https', $hrlm->scheme);
        $this->assertEquals('example.com', $hrlm->host);
        $this->assertEquals(443, $hrlm->port);
        $this->assertEquals('/index', $hrlm->path);
        $this->assertEquals('p=1024&q=2048', $hrlm->query);
        $this->assertEquals('GET', $hrlm->method);
        $this->assertEquals('HTTP/1.0', $hrlm->protocol);
        $this->assertEquals('https://example.com/index?p=1024&q=2048', $hrlm->uri);
    }

    public function testPortCompletionHTTP()
    {
        $hrlm = new HttpRequestLineMutable([
            'HTTP_HOST'       => 'example.com',
            'REQUEST_URI'     => '/index?p=1024&q=2048',
            'QUERY_STRING'    => 'p=1024&q=2048',
            'REQUEST_METHOD'  => 'GET',
            'SERVER_PROTOCOL' => 'HTTP/1.0'
        ], new RequestDataContainer);

        $this->assertEquals('http', $hrlm->scheme);
        $this->assertEquals('example.com', $hrlm->host);
        $this->assertEquals(80, $hrlm->port);
        $this->assertEquals('/index', $hrlm->path);
        $this->assertEquals('p=1024&q=2048', $hrlm->query);
        $this->assertEquals('GET', $hrlm->method);
        $this->assertEquals('HTTP/1.0', $hrlm->protocol);
        $this->assertEquals('http://example.com/index?p=1024&q=2048', $hrlm->uri);
    }


    /** @var HttpRequestLineMutable */
    private $hrlm;

    public function setUp()
    {
        $this->hrlm = new HttpRequestLineMutable([
            'HTTP_HOST'       => 'example.com',
            'REQUEST_URI'     => '/index?p=1024&q=2048',
            'QUERY_STRING'    => 'p=1024&q=2048',
            'REQUEST_METHOD'  => 'GET',
            'SERVER_PROTOCOL' => 'HTTP/1.0'
        ], new RequestDataContainer);
    }

    public function testWritePort()
    {
        $this->hrlm->port = 334;
        $this->assertEquals(334, $this->hrlm->port);
    }

    public function testPortDomainUnder()
    {
        $this->expectException(DomainException::class);
        $this->hrlm->port = -1;
    }

    public function testPortDomainOver()
    {
        $this->expectException(DomainException::class);
        $this->hrlm->port = 65536;
    }

    public function testWriteMethod()
    {
        $this->hrlm->method = RequestDataContainer::METHOD_GET;
        $this->assertEquals(RequestDataContainer::METHOD_GET, $this->hrlm->method);

        $this->hrlm->method = RequestDataContainer::METHOD_POST;
        $this->assertEquals(RequestDataContainer::METHOD_POST, $this->hrlm->method);

        $this->hrlm->method = RequestDataContainer::METHOD_PUT;
        $this->assertEquals(RequestDataContainer::METHOD_PUT, $this->hrlm->method);

        $this->hrlm->method = RequestDataContainer::METHOD_DELETE;
        $this->assertEquals(RequestDataContainer::METHOD_DELETE, $this->hrlm->method);
    }

    public function testMethodDomain()
    {
        $this->expectException(DomainException::class);
        $this->hrlm->method = '33-4';
    }

    public function testWriteProtocol()
    {
        $this->hrlm->protocol = RequestDataContainer::PROTOCOL_HTTP10;
        $this->assertEquals(RequestDataContainer::PROTOCOL_HTTP10, $this->hrlm->protocol);

        $this->hrlm->protocol = RequestDataContainer::PROTOCOL_HTTP11;
        $this->assertEquals(RequestDataContainer::PROTOCOL_HTTP11, $this->hrlm->protocol);

        $this->hrlm->protocol = RequestDataContainer::PROTOCOL_HTTP20;
        $this->assertEquals(RequestDataContainer::PROTOCOL_HTTP20, $this->hrlm->protocol);
    }

    public function testProtocolDomain()
    {
        $this->expectException(DomainException::class);
        $this->hrlm->protocol = '33-4';
    }
}