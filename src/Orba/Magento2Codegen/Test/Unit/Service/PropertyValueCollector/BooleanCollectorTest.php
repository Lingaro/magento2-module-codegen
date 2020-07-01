<?php

namespace Orba\Magento2Codegen\Test\Unit\Service\PropertyValueCollector;

use InvalidArgumentException;
use Orba\Magento2Codegen\Model\ConstProperty;
use Orba\Magento2Codegen\Model\BooleanProperty;
use Orba\Magento2Codegen\Service\IO;
use Orba\Magento2Codegen\Service\PropertyValueCollector\BooleanCollector;
use Orba\Magento2Codegen\Test\Unit\TestCase;
use Orba\Magento2Codegen\Util\PropertyBag;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\Console\Style\SymfonyStyle;

class BooleanCollectorTest extends TestCase
{
    /**
     * @var BooleanCollector
     */
    private $booleanCollector;

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

    public function testCollectValueThrowsExceptionIfPropertyIsNotBooleanProperty()
    {
        $this->expectException(InvalidArgumentException::class);
        /** @var ConstProperty|MockObject $propertyMock */
        $propertyMock = $this->getMockBuilder(ConstProperty::class)->disableOriginalConstructor()->getMock();
        $this->booleanCollector->collectValue($propertyMock, $this->propertyBagMock);
    }

    public function testCollectValueReturnsValueFromInputIfPropertyIsBooleanProperty()
    {
        $this->ioInstnaceMock->expects($this->once())->method('confirm')->willReturn(true);
        /** @var BooleanProperty|MockObject $propertyMock */
        $propertyMock = $this->getMockBuilder(BooleanProperty::class)->disableOriginalConstructor()->getMock();
        $result = $this->booleanCollector->collectValue($propertyMock, $this->propertyBagMock);
        $this->assertSame(true, $result);
    }

    public function additionProvider()
    {
        return [
            ['foo', true],
            ['yes', true],
            ['no', false],
            ['1', true],
            ['0', false]
        ];
    }
}
