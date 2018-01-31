<?php

namespace Bellisq\Request\Tests\TestCases;

use Bellisq\Request\Containers\HttpParsedVariablesImmutable;
use Bellisq\Request\Containers\HttpParsedVariablesMutable;
use Bellisq\Request\Containers\RequestDataContainer;
use PHPUnit\Framework\TestCase;


class ZZParsedVariablesImmutableTest
    extends TestCase
{
    public function testConstructionGet()
    {
        $rdc = new RequestDataContainer([
            RequestDataContainer::VAR_LINE_METHOD => RequestDataContainer::METHOD_GET
        ]);

        new HttpParsedVariablesMutable(
            ['g' => true, 'e' => false, 't' => null],
            ['p' => true, 'o' => false, 's' => true, 't' => false],
            ['f' => true, 'i' => false, 'l' => true, 'e' => false, 's' => null],
            ['c' => true, 'o' => false, 'k' => true, 'i' => false, 'e' => null],
            $rdc
        );


        $hpvm = new HttpParsedVariablesImmutable($rdc);

        $this->assertEquals(['g' => true, 'e' => false, 't' => null], $hpvm->get);
        $this->assertNull($hpvm->post);
        $this->assertNull($hpvm->files);
        $this->assertEquals(
            ['c' => true, 'o' => false, 'k' => true, 'i' => false, 'e' => null],
            $hpvm->cookie
        );
    }

    public function testConstructionPost()
    {
        $rdc = new RequestDataContainer([
            RequestDataContainer::VAR_LINE_METHOD => RequestDataContainer::METHOD_POST
        ]);

        new HttpParsedVariablesMutable(
            ['g' => true, 'e' => false, 't' => null],
            ['p' => true, 'o' => false, 's' => true, 't' => false],
            ['f' => true, 'i' => false, 'l' => true, 'e' => false, 's' => null],
            ['c' => true, 'o' => false, 'k' => true, 'i' => false, 'e' => null],
            $rdc
        );

        $hpvm = new HttpParsedVariablesImmutable($rdc);


        $this->assertEquals(['g' => true, 'e' => false, 't' => null], $hpvm->get);
        $this->assertEquals(
            ['p' => true, 'o' => false, 's' => true, 't' => false],
            $hpvm->post
        );
        $this->assertEquals(
            ['f' => true, 'i' => false, 'l' => true, 'e' => false, 's' => null],
            $hpvm->files
        );
        $this->assertEquals(
            ['c' => true, 'o' => false, 'k' => true, 'i' => false, 'e' => null],
            $hpvm->cookie
        );
    }
}