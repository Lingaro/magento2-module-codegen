<?php

namespace Orba\Magento2Codegen\Test\Unit\Service\PropertyValueCollector;

use InvalidArgumentException;
use Orba\Magento2Codegen\Model\ConstProperty;
use Orba\Magento2Codegen\Model\StringProperty;
use Orba\Magento2Codegen\Service\PropertyValueCollector\ConstCollector;
use Orba\Magento2Codegen\Test\Unit\TestCase;

class ConstCollectorTest extends TestCase
{
    /**
     * @var ConstCollector
     */
    private $constCollector;

    public function setUp(): void
    {
        $this->constCollector = new ConstCollector();
    }

    public function testCollectValueThrowsExceptionIfPropertyIsNotConstProperty()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->constCollector->collectValue(
            $this->getMockBuilder(StringProperty::class)->disableOriginalConstructor()->getMock()
        );
    }

    public function testCollectValueReturnsPropertyValueIfPropertyIsConstProperty()
    {
        $propertyMock = $this->getMockBuilder(ConstProperty::class)->disableOriginalConstructor()->getMock();
        $propertyMock->expects($this->once())->method('getValue')->willReturn('foo');
        $result = $this->constCollector->collectValue($propertyMock);
        $this->assertSame('foo', $result);
    }
}