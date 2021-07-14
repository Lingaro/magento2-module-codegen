<?php

/**
 * @copyright Copyright © 2021 Orba. All rights reserved.
 * @author    info@orba.co
 */

declare(strict_types=1);

namespace Orba\Magento2Codegen\Test\Unit\Service\PropertyValueCollector;

use InvalidArgumentException;
use Orba\Magento2Codegen\Model\ArrayProperty;
use Orba\Magento2Codegen\Model\ConstProperty;
use Orba\Magento2Codegen\Model\PropertyInterface;
use Orba\Magento2Codegen\Service\IO;
use Orba\Magento2Codegen\Service\PropertyDependencyChecker;
use Orba\Magento2Codegen\Service\PropertyValueCollector\ArrayCollector;
use Orba\Magento2Codegen\Service\PropertyValueCollector\CollectorFactory;
use Orba\Magento2Codegen\Test\Unit\TestCase;
use Orba\Magento2Codegen\Util\PropertyBag;
use PHPUnit\Framework\MockObject\MockObject;
use RuntimeException;
use Symfony\Component\Console\Style\SymfonyStyle;

class ArrayCollectorTest extends TestCase
{
    private ArrayCollector $arrayCollector;

    /**
     * @var MockObject|IO
     */
    private $ioMock;

    /**
     * @var MockObject|SymfonyStyle
     */
    private $ioInstanceMock;

    /**
     * @var MockObject|PropertyDependencyChecker
     */
    private $propertyDependencyCheckerMock;

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
        $this->propertyDependencyCheckerMock = $this->getMockBuilder(PropertyDependencyChecker::class)
            ->disableOriginalConstructor()->getMock();
        $this->propertyBagMock = $this->getMockBuilder(PropertyBag::class)->disableOriginalConstructor()
            ->getMock();
        $this->arrayCollector = new ArrayCollector($this->ioMock, $this->propertyDependencyCheckerMock);
    }

    public function testCollectValueThrowsExceptionIfPropertyIsNotArrayProperty(): void
    {
        $this->expectException(InvalidArgumentException::class);
        /** @var ConstProperty|MockObject $propertyMock */
        $propertyMock = $this->getMockBuilder(ConstProperty::class)->disableOriginalConstructor()->getMock();
        $this->arrayCollector->collectValue($propertyMock, $this->propertyBagMock);
    }

    public function testCollectValueThrowsExceptionIfCollectorFactoryIsUnset(): void
    {
        $this->expectException(RuntimeException::class);
        /** @var ArrayProperty|MockObject $propertyMock */
        $propertyMock = $this->getMockBuilder(ArrayProperty::class)->disableOriginalConstructor()->getMock();
        $this->arrayCollector->collectValue($propertyMock, $this->propertyBagMock);
    }

    public function testCollectValueReturnsEmptyArrayIfPropertyIsNotRequiredAndInteractionDidNotHappen(): void
    {
        /** @var MockObject|ArrayProperty $propertyMock */
        $propertyMock = $this->getMockBuilder(ArrayProperty::class)->disableOriginalConstructor()->getMock();
        $propertyMock->expects($this->any())->method('getRequired')->willReturn(false);
        $this->ioInstanceMock->expects($this->any())->method('confirm')->willReturn(false);
        $this->setCollectorFactory();
        $result = $this->arrayCollector->collectValue($propertyMock, $this->propertyBagMock);
        $this->assertSame([], $result);
    }

    public function testCollectValueReturnsArrayWithAtLeastOneItemIfPropertyIsNotRequiredButInteractionHappened(): void
    {
        /** @var MockObject|PropertyInterface $childMock */
        $childMock = $this->getMockBuilder(PropertyInterface::class)->getMockForAbstractClass();
        /** @var MockObject|ArrayProperty $propertyMock */
        $propertyMock = $this->getMockBuilder(ArrayProperty::class)->disableOriginalConstructor()->getMock();
        $propertyMock->expects($this->once())->method('getChildren')->willReturn([$childMock]);
        $propertyMock->expects($this->any())->method('getRequired')->willReturn(false);
        $this->ioInstanceMock->expects($this->any())->method('confirm')->willReturnOnConsecutiveCalls(true, false);
        $this->setCollectorFactory();
        $result = $this->arrayCollector->collectValue($propertyMock, $this->propertyBagMock);
        $this->assertIsArray($result);
        $this->assertCount(1, $result);
    }

    public function testCollectValueReturnsArrayWithAtLeastOneItemIfPropertyIsRequired(): void
    {
        /** @var MockObject|PropertyInterface $childMock */
        $childMock = $this->getMockBuilder(PropertyInterface::class)->getMockForAbstractClass();
        /** @var MockObject|ArrayProperty $propertyMock */
        $propertyMock = $this->getMockBuilder(ArrayProperty::class)->disableOriginalConstructor()->getMock();
        $propertyMock->expects($this->once())->method('getChildren')->willReturn([$childMock]);
        $propertyMock->expects($this->any())->method('getRequired')->willReturn(true);
        $this->ioInstanceMock->expects($this->any())->method('confirm')->willReturn(false);
        $this->setCollectorFactory();
        $result = $this->arrayCollector->collectValue($propertyMock, $this->propertyBagMock);
        $this->assertIsArray($result);
        $this->assertCount(1, $result);
    }

    private function setCollectorFactory(): void
    {
        /** @var CollectorFactory|MockObject $collectorFactoryMock */
        $collectorFactoryMock = $this->getMockBuilder(CollectorFactory::class)->disableOriginalConstructor()
            ->getMock();
        $this->arrayCollector->setCollectorFactory($collectorFactoryMock);
    }
}
