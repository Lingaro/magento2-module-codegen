<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Lingaro\Magento2Codegen\Test\Unit\Service\PropertyValueCollector;

use InvalidArgumentException;
use Lingaro\Magento2Codegen\Model\ConstProperty;
use Lingaro\Magento2Codegen\Model\StringProperty;
use Lingaro\Magento2Codegen\Service\PropertyValueCollector\ConstCollector;
use Lingaro\Magento2Codegen\Test\Unit\TestCase;
use Lingaro\Magento2Codegen\Util\PropertyBag;
use PHPUnit\Framework\MockObject\MockObject;

class ConstCollectorTest extends TestCase
{
    private ConstCollector $constCollector;

    /**
     * @var MockObject|PropertyBag
     */
    private $propertyBagMock;

    public function setUp(): void
    {
        $this->propertyBagMock = $this->getMockBuilder(PropertyBag::class)->disableOriginalConstructor()
            ->getMock();
        $this->constCollector = new ConstCollector();
    }

    public function testCollectValueThrowsExceptionIfPropertyIsNotConstProperty(): void
    {
        $this->expectException(InvalidArgumentException::class);
        /** @var StringProperty|MockObject $propertyMock */
        $propertyMock = $this->getMockBuilder(StringProperty::class)->disableOriginalConstructor()->getMock();
        $this->constCollector->collectValue($propertyMock, $this->propertyBagMock);
    }

    public function testCollectValueReturnsPropertyValueIfPropertyIsConstProperty(): void
    {
        /** @var ConstProperty|MockObject $propertyMock */
        $propertyMock = $this->getMockBuilder(ConstProperty::class)->disableOriginalConstructor()->getMock();
        $propertyMock->expects($this->once())->method('getValue')->willReturn('foo');
        $result = $this->constCollector->collectValue($propertyMock, $this->propertyBagMock);
        $this->assertSame('foo', $result);
    }
}
