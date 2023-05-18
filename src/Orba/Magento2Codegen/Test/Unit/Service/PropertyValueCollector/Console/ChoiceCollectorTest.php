<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Orba\Magento2Codegen\Test\Unit\Service\PropertyValueCollector\Console;

use InvalidArgumentException;
use Orba\Magento2Codegen\Model\ChoiceProperty;
use Orba\Magento2Codegen\Model\ConstProperty;
use Orba\Magento2Codegen\Service\IO;
use Orba\Magento2Codegen\Service\PropertyValueCollector\Console\ChoiceCollector;
use Orba\Magento2Codegen\Test\Unit\TestCase;
use Orba\Magento2Codegen\Util\PropertyBag;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\Console\Style\SymfonyStyle;

class ChoiceCollectorTest extends TestCase
{
    private ChoiceCollector $choiceCollector;

    /**
     * @var MockObject|IO
     */
    private $ioMock;

    /**
     * @var MockObject|SymfonyStyle
     */
    private $ioInstanceMock;

    /**
     * @var MockObject|PropertyBag
     */
    private $propertyBagMock;

    public function setUp(): void
    {
        $this->ioInstanceMock = $this->getMockBuilder(SymfonyStyle::class)
            ->disableOriginalConstructor()->getMock();
        $this->ioMock = $this->getMockBuilder(IO::class)->disableOriginalConstructor()->getMock();
        $this->ioMock->expects($this->any())->method('getInstance')->willReturn($this->ioInstanceMock);
        $this->propertyBagMock = $this->getMockBuilder(PropertyBag::class)->disableOriginalConstructor()
            ->getMock();
        $this->choiceCollector = new ChoiceCollector($this->ioMock);
    }

    public function testCollectValueThrowsExceptionIfPropertyIsNotChoiceProperty(): void
    {
        $this->expectException(InvalidArgumentException::class);
        /** @var ConstProperty|MockObject $propertyMock */
        $propertyMock = $this->getMockBuilder(ConstProperty::class)->disableOriginalConstructor()->getMock();
        $this->choiceCollector->collectValue($propertyMock, $this->propertyBagMock);
    }

    public function testCollectValueReturnsValueFromInputIfPropertyIsChoiceProperty(): void
    {
        $this->ioInstanceMock->expects($this->once())->method('askQuestion')->willReturn('foo');
        /** @var ChoiceProperty|MockObject $propertyMock */
        $propertyMock = $this->getMockBuilder(ChoiceProperty::class)->disableOriginalConstructor()->getMock();
        $propertyMock->expects($this->any())->method('getOptions')->willReturn(['bar', 'baz']);
        $result = $this->choiceCollector->collectValue($propertyMock, $this->propertyBagMock);
        $this->assertSame('foo', $result);
    }
}
