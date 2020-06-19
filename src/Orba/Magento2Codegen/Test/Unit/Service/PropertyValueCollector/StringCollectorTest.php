<?php

namespace Orba\Magento2Codegen\Test\Unit\Service\PropertyValueCollector;

use InvalidArgumentException;
use Orba\Magento2Codegen\Model\ConstProperty;
use Orba\Magento2Codegen\Model\StringProperty;
use Orba\Magento2Codegen\Service\IO;
use Orba\Magento2Codegen\Service\PropertyValueCollector\StringCollector;
use Orba\Magento2Codegen\Test\Unit\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

class StringCollectorTest extends TestCase
{
    /**
     * @var StringCollector
     */
    private $stringCollector;

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
        $this->stringCollector = new StringCollector($this->ioMock);
    }

    public function testCollectValueThrowsExceptionIfPropertyIsNotStringProperty()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->stringCollector->collectValue(
            $this->getMockBuilder(ConstProperty::class)->disableOriginalConstructor()->getMock()
        );
    }

    public function testCollectValueReturnsValueFromInputIfPropertyIsStringProperty()
    {
        $this->ioInstanceMock->expects($this->once())->method('askQuestion')
            ->willReturn('foo');
        $result = $this->stringCollector->collectValue(
            $this->getMockBuilder(StringProperty::class)->disableOriginalConstructor()->getMock()
        );
        $this->assertSame('foo', $result);
    }

    public function testCollectValueReturnsValueIfPropertyIsRequired()
    {
        $stringPropertyMock = $this->getMockBuilder(StringProperty::class)->disableOriginalConstructor()->getMock();
        $stringPropertyMock->expects($this->once())->method('getRequired')->willReturn(true);
        /** @var $subject Question */
        $this->ioInstanceMock->expects($this->once())->method('askQuestion')
            ->with($this->callback(function ($subject) {
                $this->assertIsCallable($subject->getValidator());
                return $subject instanceof Question;
            }))
            ->willReturn('foo');
        $result = $this->stringCollector->collectValue($stringPropertyMock);
        $this->assertSame('foo', $result);
    }
}