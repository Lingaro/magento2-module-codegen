<?php

namespace Orba\Magento2Codegen\Test\Unit\Service\PropertyValueCollector;

use InvalidArgumentException;
use Orba\Magento2Codegen\Model\ArrayProperty;
use Orba\Magento2Codegen\Model\ConstProperty;
use Orba\Magento2Codegen\Model\PropertyInterface;
use Orba\Magento2Codegen\Service\IO;
use Orba\Magento2Codegen\Service\PropertyValueCollector\ArrayCollector;
use Orba\Magento2Codegen\Service\PropertyValueCollector\CollectorFactory;
use Orba\Magento2Codegen\Test\Unit\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use RuntimeException;
use Symfony\Component\Console\Style\SymfonyStyle;
use Orba\Magento2Codegen\Service\PropertyValueCollector\CollectorInterface;

class ArrayCollectorTest extends TestCase
{
    /**
     * @var ArrayCollector
     */
    private $arrayCollector;

    /**
     * @var MockObject|IO
     */
    private $ioMock;

    /**
     * @var MockObject|SymfonyStyle
     */
    private $ioInstanceMock;

    public function setUp(): void
    {
        $this->ioInstanceMock = $this->getMockBuilder(SymfonyStyle::class)
            ->disableOriginalConstructor()->getMock();
        $this->ioMock = $this->getMockBuilder(IO::class)->disableOriginalConstructor()->getMock();
        $this->ioMock->expects($this->any())->method('getInstance')->willReturn($this->ioInstanceMock);
        $this->arrayCollector = new ArrayCollector($this->ioMock);
    }

    public function testCollectValueThrowsExceptionIfPropertyIsNotArrayProperty(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->arrayCollector->collectValue(
            $this->getMockBuilder(ConstProperty::class)->disableOriginalConstructor()->getMock()
        );
    }

    public function testCollectValueThrowsExceptionIfCollectorFactoryIsUnset(): void
    {
        $this->expectException(RuntimeException::class);
        $this->arrayCollector->collectValue(
            $this->getMockBuilder(ArrayProperty::class)->disableOriginalConstructor()->getMock()
        );
    }

    public function testCollectValueReturnsEmptyArrayIfPropertyIsNotRequiredAndInteractionDidNotHappen(): void
    {
        /** @var MockObject|ArrayProperty $propertyMock */
        $propertyMock = $this->getMockBuilder(ArrayProperty::class)->disableOriginalConstructor()->getMock();
        $propertyMock->expects($this->any())->method('getRequired')->willReturn(false);
        $this->ioInstanceMock->expects($this->any())->method('confirm')->willReturn(false);
        $this->arrayCollector->setCollectorFactory(
            $this->getMockBuilder(CollectorFactory::class)->disableOriginalConstructor()->getMock()
        );
        $result = $this->arrayCollector->collectValue($propertyMock);
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
        $this->ioInstanceMock->expects($this->at(0))->method('confirm')->willReturn(true);
        $this->arrayCollector->setCollectorFactory(
            $this->getMockBuilder(CollectorFactory::class)->disableOriginalConstructor()->getMock()
        );
        $result = $this->arrayCollector->collectValue($propertyMock);
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
        $this->arrayCollector->setCollectorFactory(
            $this->getMockBuilder(CollectorFactory::class)->disableOriginalConstructor()->getMock()
        );
        $result = $this->arrayCollector->collectValue($propertyMock);
        $this->assertIsArray($result);
        $this->assertCount(1, $result);
    }

    /**
     * @dataProvider hasInvalidNameProvider
     * @param string $parentChildName
     */
    public function testCollectValueDependentAttributeThrowExceptionIfDependentAttributeHasInvalidName(
        string $parentChildName
    ) {
        $parentChildMock = $this->getMockBuilder(PropertyInterface::class)->getMockForAbstractClass();
        $parentChildMock->expects($this->atLeastOnce())->method('getName')->willReturn('parentChildMock');
        $parentChildMock->expects($this->atLeastOnce())->method('getDepend')->willReturn(null);

        $dependentChildMock = $this->getMockBuilder(PropertyInterface::class)->getMockForAbstractClass();
        $dependentChildMock->expects($this->atLeastOnce())->method('getDepend')->willReturn([$parentChildName => true]);


        $propertyMock = $this->getMockBuilder(ArrayProperty::class)->disableOriginalConstructor()->getMock();
        $propertyMock->expects($this->atLeastOnce())->method('getName')->willReturn('parentArray');
        $propertyMock->expects($this->once())->method('getChildren')->willReturn([
            $parentChildMock,
            $dependentChildMock
        ]);


        $this->ioInstanceMock->expects($this->never())->method('confirm')->willReturn(false);
        $this->arrayCollector->setCollectorFactory(
            $this->getMockBuilder(CollectorFactory::class)->disableOriginalConstructor()->getMock()
        );
        $this->expectException(RuntimeException::class);
        $this->arrayCollector->collectValue($propertyMock);
    }

    /**
     * @return array|string[][]
     */
    public function hasInvalidNameProvider(): array
    {
        return [
            ['parentChildMock'],
            ['parentArray.parentChildMock']
        ];
    }

    public function testCollectValueDependentAttributeIfParentHasExpectedValue()
    {
        $parentChildMock = $this->getMockBuilder(PropertyInterface::class)->getMockForAbstractClass();
        $parentChildMock->expects($this->atLeastOnce())->method('getName')->willReturn('parentChildMock');
        $parentChildMock->expects($this->atLeastOnce())->method('getDepend')->willReturn(null);

        $dependentChildMock = $this->getMockBuilder(PropertyInterface::class)->getMockForAbstractClass();
        $dependentChildMock->expects($this->atLeastOnce())->method('getName')->willReturn('dependentChildMock');
        $dependentChildMock->expects($this->atLeastOnce())->method('getDepend')->willReturn(['item.parentChildMock' => true]);

        $propertyMock = $this->getMockBuilder(ArrayProperty::class)->disableOriginalConstructor()->getMock();
        $propertyMock->expects($this->any())->method('getName')->willReturn('parentArray');
        $propertyMock->expects($this->once())->method('getChildren')->willReturn([
            $parentChildMock,
            $dependentChildMock
        ]);

        $collectorFactoryMock = $this->getMockBuilder(CollectorFactory::class)->disableOriginalConstructor()->getMock();
        $collectorMock = $this->getMockBuilder(CollectorInterface::class)->getMockForAbstractClass();
        $collectorMock->expects($this->exactly(2))
            ->method('collectValue')
            ->withConsecutive([$parentChildMock], [$dependentChildMock])
            ->willReturnOnConsecutiveCalls(true, false);
        $collectorFactoryMock->expects($this->any())->method('create')->willReturn($collectorMock);

        $this->ioInstanceMock->expects($this->once())->method('confirm')->willReturn(false);
        $this->arrayCollector->setCollectorFactory($collectorFactoryMock);
        $result = $this->arrayCollector->collectValue($propertyMock);
        $expected = [
            [
                'parentChildMock' => true,
                'dependentChildMock' => false
            ]
        ];
        $this->assertIsArray($result);
        $this->assertSame($expected, $result);
    }

}
