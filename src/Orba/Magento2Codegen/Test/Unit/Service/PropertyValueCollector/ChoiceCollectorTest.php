<?php

namespace Orba\Magento2Codegen\Test\Unit\Service\PropertyValueCollector;

use InvalidArgumentException;
use Orba\Magento2Codegen\Model\ChoiceProperty;
use Orba\Magento2Codegen\Model\ConstProperty;
use Orba\Magento2Codegen\Service\IO;
use Orba\Magento2Codegen\Service\PropertyValueCollector\ChoiceCollector;
use Orba\Magento2Codegen\Test\Unit\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\Console\Style\SymfonyStyle;

class ChoiceCollectorTest extends TestCase
{
    /**
     * @var ChoiceCollector
     */
    private $choiceCollector;

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
        $this->choiceCollector = new ChoiceCollector($this->ioMock);
    }

    public function testCollectValueThrowsExceptionIfPropertyIsNotChoiceProperty(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->choiceCollector->collectValue(
            $this->getMockBuilder(ConstProperty::class)->disableOriginalConstructor()->getMock()
        );
    }

    public function testCollectValueReturnsValueFromInputIfPropertyIsChoiceProperty(): void
    {
        $this->ioInstnaceMock->expects($this->once())->method('askQuestion')->willReturn('foo');
        /** @var ChoiceProperty|MockObject $propertyMock */
        $propertyMock = $this->getMockBuilder(ChoiceProperty::class)->disableOriginalConstructor()->getMock();
        $propertyMock->expects($this->any())->method('getOptions')->willReturn(['bar', 'baz']);
        $result = $this->choiceCollector->collectValue($propertyMock);
        $this->assertSame('foo', $result);
    }
}
