<?php

namespace Bellisq\Request\Tests\TestCases;

use Bellisq\Request\Containers\HttpParsedVariablesMutable;
use Bellisq\Request\Containers\RequestDataContainer;
use PHPUnit\Framework\TestCase;


class ZZParsedVariablesMutableTest
    extends TestCase
{
    public function testConstructionGet()
    {
        $rdc = new RequestDataContainer([
            RequestDataContainer::VAR_LINE_METHOD => RequestDataContainer::METHOD_GET
        ]);

        $hpvm = new HttpParsedVariablesMutable(
            ['g' => true, 'e' => false, 't' => null],
            ['p' => true, 'o' => false, 's' => true, 't' => false],
            ['f' => true, 'i' => false, 'l' => true, 'e' => false, 's' => null],
            ['c' => true, 'o' => false, 'k' => true, 'i' => false, 'e' => null],
            $rdc
        );

        $this->assertEquals(['g' => true, 'e' => false, 't' => null], $hpvm->get->getArrayCopy());
        $this->assertNull($hpvm->post);
        $this->assertNull($hpvm->files);
        $this->assertEquals(
            ['c' => true, 'o' => false, 'k' => true, 'i' => false, 'e' => null],
            $hpvm->cookie->getArrayCopy()
        );
    }

    public function testConstructionPost()
    {
        $rdc = new RequestDataContainer([
            RequestDataContainer::VAR_LINE_METHOD => RequestDataContainer::METHOD_POST
        ]);

        $hpvm = new HttpParsedVariablesMutable(
            ['g' => true, 'e' => false, 't' => null],
            ['p' => true, 'o' => false, 's' => true, 't' => false],
            ['f' => true, 'i' => false, 'l' => true, 'e' => false, 's' => null],
            ['c' => true, 'o' => false, 'k' => true, 'i' => false, 'e' => null],
            $rdc
        );

        $this->assertEquals(['g' => true, 'e' => false, 't' => null], $hpvm->get->getArrayCopy());
        $this->assertEquals(
            ['p' => true, 'o' => false, 's' => true, 't' => false],
            $hpvm->post->getArrayCopy()
        );
        $this->assertEquals(
            ['f' => true, 'i' => false, 'l' => true, 'e' => false, 's' => null],
            $hpvm->files->getArrayCopy()
        );
        $this->assertEquals(
            ['c' => true, 'o' => false, 'k' => true, 'i' => false, 'e' => null],
            $hpvm->cookie->getArrayCopy()
        );
    }
}