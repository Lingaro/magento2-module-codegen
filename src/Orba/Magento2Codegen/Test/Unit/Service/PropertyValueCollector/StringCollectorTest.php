<?php

namespace Orba\Magento2Codegen\Test\Unit\Service\PropertyValueCollector;

use InvalidArgumentException;
use Orba\Magento2Codegen\Model\ConstProperty;
use Orba\Magento2Codegen\Model\StringProperty;
use Orba\Magento2Codegen\Service\IO;
use Orba\Magento2Codegen\Service\PropertyValueCollector\StringCollector;
use Orba\Magento2Codegen\Test\Unit\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
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
    private $ioInstnaceMock;

    public function setUp(): void
    {
        $this->ioInstnaceMock = $this->getMockBuilder(SymfonyStyle::class)
            ->disableOriginalConstructor()->getMock();
        $this->ioMock = $this->getMockBuilder(IO::class)->disableOriginalConstructor()->getMock();
        $this->ioMock->expects($this->any())->method('getInstance')->willReturn($this->ioInstnaceMock);
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
        $this->ioInstnaceMock->expects($this->once())->method('ask')->willReturn('foo');
        $result = $this->stringCollector->collectValue(
            $this->getMockBuilder(StringProperty::class)->disableOriginalConstructor()->getMock()
        );
        $this->assertSame('foo', $result);
    }
}