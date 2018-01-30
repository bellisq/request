<?php

namespace Bellisq\Request\Tests\TestCases;

use Bellisq\Request\Containers\MutableArray;
use Bellisq\Request\Containers\RequestDataContainer;
use PHPUnit\Framework\TestCase;


class ZZRequestDataContainerTest
    extends TestCase
{
    public function testMethodChanged()
    {
        $k = new RequestDataContainer([
            RequestDataContainer::VAR_LINE_METHOD  => RequestDataContainer::METHOD_POST,
            RequestDataContainer::VAR_BODY         => null,
            RequestDataContainer::VAR_PARSED_POST  => null,
            RequestDataContainer::VAR_PARSED_FILES => null
        ]);

        $k->methodChanged();

        $this->assertNull($k[RequestDataContainer::VAR_BODY]);
        $this->assertInstanceOf(MutableArray::class, $k[RequestDataContainer::VAR_PARSED_POST]);
        $this->assertInstanceOf(MutableArray::class, $k[RequestDataContainer::VAR_PARSED_FILES]);

        $k[RequestDataContainer::VAR_LINE_METHOD] = RequestDataContainer::METHOD_PUT;
        $k->methodChanged();

        $this->assertTrue('' === $k[RequestDataContainer::VAR_BODY]);
        $this->assertNull($k[RequestDataContainer::VAR_PARSED_POST]);
        $this->assertNull($k[RequestDataContainer::VAR_PARSED_FILES]);


        $k[RequestDataContainer::VAR_LINE_METHOD] = RequestDataContainer::METHOD_GET;
        $k->methodChanged();

        $this->assertNull($k[RequestDataContainer::VAR_BODY]);
        $this->assertNull($k[RequestDataContainer::VAR_PARSED_POST]);
        $this->assertNull($k[RequestDataContainer::VAR_PARSED_FILES]);
    }
}