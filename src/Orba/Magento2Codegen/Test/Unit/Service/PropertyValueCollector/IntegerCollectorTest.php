<?php

namespace Orba\Magento2Codegen\Test\Unit\Service\PropertyValueCollector;

use InvalidArgumentException;
use Orba\Magento2Codegen\Model\ConstProperty;
use Orba\Magento2Codegen\Model\IntegerProperty;
use Orba\Magento2Codegen\Service\IO;
use Orba\Magento2Codegen\Service\PropertyValueCollector\IntegerCollector;
use Orba\Magento2Codegen\Test\Unit\TestCase;
use Orba\Magento2Codegen\Util\PropertyBag;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

class IntegerCollectorTest extends TestCase
{
    /**
     * @var IntegerCollector
     */
    private $integerCollector;

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
        $this->integerCollector = new IntegerCollector($this->ioMock);
    }

    public function testCollectValueThrowsExceptionIfPropertyIsNotIntegerProperty()
    {
        $this->expectException(InvalidArgumentException::class);
        /** @var ConstProperty|MockObject $propertyMock */
        $propertyMock = $this->getMockBuilder(ConstProperty::class)->disableOriginalConstructor()->getMock();
        $this->integerCollector->collectValue($propertyMock, $this->propertyBagMock);
    }

    public function testCollectValueReturnsValueFromInputIfPropertyIsIntegerProperty()
    {
        $this->ioInstanceMock->expects($this->once())->method('askQuestion')
            ->willReturn(123);
        /** @var IntegerProperty|MockObject $propertyMock */
        $propertyMock = $this->getMockBuilder(IntegerProperty::class)->disableOriginalConstructor()->getMock();
        $result = $this->integerCollector->collectValue($propertyMock, $this->propertyBagMock);
        $this->assertSame(123, $result);
    }

    public function testCollectValueReturnsValueIfPropertyIsRequired()
    {
        /** @var IntegerProperty|MockObject $integerPropertyMock */
        $integerPropertyMock = $this->getMockBuilder(IntegerProperty::class)->disableOriginalConstructor()->getMock();
        $integerPropertyMock->expects($this->any())->method('getRequired')->willReturn(true);
        /** @var $subject Question */
        $this->ioInstanceMock->expects($this->once())->method('askQuestion')
            ->with($this->callback(function ($subject) {
                $this->assertIsCallable($subject->getValidator());
                return $subject instanceof Question;
            }))
            ->willReturn(123);
        $result = $this->integerCollector->collectValue($integerPropertyMock, $this->propertyBagMock);
        $this->assertSame(123, $result);
    }
}
