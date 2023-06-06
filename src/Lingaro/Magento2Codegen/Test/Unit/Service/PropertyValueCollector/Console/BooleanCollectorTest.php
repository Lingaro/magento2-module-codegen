<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Lingaro\Magento2Codegen\Test\Unit\Service\PropertyValueCollector\Console;

use InvalidArgumentException;
use Lingaro\Magento2Codegen\Model\ConstProperty;
use Lingaro\Magento2Codegen\Model\BooleanProperty;
use Lingaro\Magento2Codegen\Service\IO;
use Lingaro\Magento2Codegen\Service\PropertyValueCollector\Console\BooleanCollector;
use Lingaro\Magento2Codegen\Test\Unit\TestCase;
use Lingaro\Magento2Codegen\Util\PropertyBag;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\Console\Style\SymfonyStyle;

class BooleanCollectorTest extends TestCase
{
    private BooleanCollector $booleanCollector;

    /**
     * @var MockObject|IO
     */
    private $ioMock;

    /**
     * @var MockObject|SymfonyStyle
     */
    private $ioInstnaceMock;

    /**
     * @var MockObject|PropertyBag
     */
    private $propertyBagMock;

    public function setUp(): void
    {
        $this->ioInstnaceMock = $this->getMockBuilder(SymfonyStyle::class)
            ->disableOriginalConstructor()->getMock();
        $this->ioMock = $this->getMockBuilder(IO::class)->disableOriginalConstructor()->getMock();
        $this->ioMock->expects($this->any())->method('getInstance')->willReturn($this->ioInstnaceMock);
        $this->propertyBagMock = $this->getMockBuilder(PropertyBag::class)->disableOriginalConstructor()
            ->getMock();
        $this->booleanCollector = new BooleanCollector($this->ioMock);
    }

    public function testCollectValueThrowsExceptionIfPropertyIsNotBooleanProperty(): void
    {
        $this->expectException(InvalidArgumentException::class);
        /** @var ConstProperty|MockObject $propertyMock */
        $propertyMock = $this->getMockBuilder(ConstProperty::class)->disableOriginalConstructor()->getMock();
        $this->booleanCollector->collectValue($propertyMock, $this->propertyBagMock);
    }

    public function testCollectValueReturnsValueFromInputIfPropertyIsBooleanProperty(): void
    {
        $this->ioInstnaceMock->expects($this->once())->method('confirm')->willReturn(true);
        /** @var BooleanProperty|MockObject $propertyMock */
        $propertyMock = $this->getMockBuilder(BooleanProperty::class)->disableOriginalConstructor()->getMock();
        $result = $this->booleanCollector->collectValue($propertyMock, $this->propertyBagMock);
        $this->assertSame(true, $result);
    }
}
