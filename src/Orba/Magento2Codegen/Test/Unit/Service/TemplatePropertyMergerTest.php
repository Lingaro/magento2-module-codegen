<?php

namespace Orba\Magento2Codegen\Test\Unit\Service;

use InvalidArgumentException;
use Orba\Magento2Codegen\Service\TemplatePropertyMerger;
use Orba\Magento2Codegen\Test\Unit\TestCase;

class TemplatePropertyMergerTest extends TestCase
{
    /**
     * @var TemplatePropertyMerger
     */
    private $templatePropertyMerger;

    public function setUp(): void
    {
        $this->templatePropertyMerger = new TemplatePropertyMerger();
    }

    public function testMergeMergesTwoDifferentScalarProperties(): void
    {
        $result = $this->templatePropertyMerger->merge(['foo' => null], ['bar' => null]);
        $this->assertCount(2, $result);
        $this->assertTrue(array_key_exists('foo', $result));
        $this->assertTrue(array_key_exists('bar', $result));
    }

    public function testMergeMergesTwoSameScalarProperties(): void
    {
        $result = $this->templatePropertyMerger->merge(['foo' => null], ['foo' => null]);
        $this->assertSame(['foo' => null], $result);
    }

    public function testMergeMergesScalarPropertyWithArrayProperty(): void
    {
        $result = $this->templatePropertyMerger->merge(['foo' => null], ['bar' => ['goo']]);
        $this->assertCount(2, $result);
        $this->assertTrue(array_key_exists('foo', $result));
        $this->assertTrue(array_key_exists('bar', $result));
        $this->assertSame(['goo'], $result['bar']);
    }

    public function testMergeMergesTwoDifferentArrayProperties(): void
    {
        $result = $this->templatePropertyMerger->merge(['foo' => ['moo']], ['bar' => ['goo']]);
        $this->assertCount(2, $result);
        $this->assertTrue(array_key_exists('foo', $result));
        $this->assertTrue(array_key_exists('bar', $result));
        $this->assertSame(['moo'], $result['foo']);
        $this->assertSame(['goo'], $result['bar']);
    }

    public function testMergeMergesTwoSameArrayProperties(): void
    {
        $result = $this->templatePropertyMerger->merge(['foo' => ['bar']], ['foo' => ['bar']]);
        $this->assertSame(['foo' => ['bar']], $result);
    }

    public function testMergeMergesTwoArrayPropertiesWithSameKeysAndDifferentElements(): void
    {
        $result = $this->templatePropertyMerger->merge(['foo' => ['bar']], ['foo' => ['moo']]);
        $this->assertCount(1, $result);
        $this->assertCount(2, $result['foo']);
        $this->assertTrue(in_array('bar', $result['foo']));
        $this->assertTrue(in_array('moo', $result['foo']));
    }

    public function testMergeThrowsExceptionWhenTryingToMergeScalarPropertyWithArray(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->templatePropertyMerger->merge(['foo' => null], ['foo' => ['bar']]);
    }

    public function testMergeThrowsExceptionWhenTryingToMergeArrayPropertyWithScalar(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->templatePropertyMerger->merge(['foo' => ['bar']], ['foo' => null]);
    }
}