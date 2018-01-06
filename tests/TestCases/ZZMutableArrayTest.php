<?php

namespace Bellisq\Request\Tests\TestCases;

use Bellisq\Request\Containers\MutableArray;
use Bellisq\Request\Exceptions\IllegalOffsetTypeException;
use Bellisq\Request\Exceptions\UndefinedOffsetException;
use PHPUnit\Framework\TestCase;
use stdClass;


class ZZMutableArrayTest
    extends TestCase
{
    public function testBehavior()
    {
        $ma = new MutableArray([
            '33-4' => 29,
        ]);

        $this->assertEquals(29, $ma['33-4']);

        $this->assertFalse(isset($ma['newElement']));
        $ma['newElement'] = 'nandeya';
        $this->assertTrue(isset($ma['newElement']));
        unset($ma['newElement']);
        $this->assertFalse(isset($ma['newElement']));

        $ma['33-4']++;
        $this->assertEquals(30, $ma['33-4']);

        $this->assertEquals([
            '33-4' => 30
        ], $ma->getArrayCopy());
    }

    public function testClone()
    {
        $std = new stdClass();
        $ma = new MutableArray(['s' => ['u' => $std]]);
        $ma2 = clone $ma;
        $ma3 = $ma;

        $this->assertTrue($std !== $ma2['s']['u']);
        $this->assertTrue($std == $ma2['s']['u']);

        $this->assertTrue($std === $ma3['s']['u']);
        $this->assertTrue($std == $ma3['s']['u']);
    }

    public function testUndefinedOffset1()
    {
        $ma = new MutableArray;
        $this->expectException(UndefinedOffsetException::class);
        $ma['d1']['d2'] = 3;
    }

    public function testUndefinedOffset2()
    {
        $ma = new MutableArray;
        $this->expectException(UndefinedOffsetException::class);
        $ma['d1'];
    }

    public function testIllegalOffsetType()
    {
        $ma = new MutableArray;
        $this->expectException(IllegalOffsetTypeException::class);
        $ma[$ma] = 33 - 4;
    }
}