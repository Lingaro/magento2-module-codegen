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
    private $ioInstnaceMock;

    public function setUp(): void
    {
        $this->ioInstnaceMock = $this->getMockBuilder(SymfonyStyle::class)
            ->disableOriginalConstructor()->getMock();
        $this->ioMock = $this->getMockBuilder(IO::class)->disableOriginalConstructor()->getMock();
        $this->ioMock->expects($this->any())->method('getInstance')->willReturn($this->ioInstnaceMock);
        $this->arrayCollector = new ArrayCollector($this->ioMock);
    }

    public function testCollectValueThrowsExceptionIfPropertyIsNotArrayProperty()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->arrayCollector->collectValue(
            $this->getMockBuilder(ConstProperty::class)->disableOriginalConstructor()->getMock()
        );
    }

    public function testCollectValueThrowsExceptionIfCollectorFactoryIsUnset()
    {
        $this->expectException(RuntimeException::class);
        $this->arrayCollector->collectValue(
            $this->getMockBuilder(ArrayProperty::class)->disableOriginalConstructor()->getMock()
        );
    }

    public function testCollectValueReturnsArrayWithAtLeastOneItem()
    {
        $childMock = $this->getMockBuilder(PropertyInterface::class)->getMockForAbstractClass();
        $propertyMock = $this->getMockBuilder(ArrayProperty::class)->disableOriginalConstructor()->getMock();
        $propertyMock->expects($this->once())->method('getChildren')->willReturn([$childMock]);
        $this->ioInstnaceMock->expects($this->any())->method('confirm')->willReturn(false);
        $this->arrayCollector->setCollectorFactory(
            $this->getMockBuilder(CollectorFactory::class)->disableOriginalConstructor()->getMock()
        );
        $result = $this->arrayCollector->collectValue($propertyMock);
        $this->assertIsArray($result);
        $this->assertCount(1, $result);
    }
}