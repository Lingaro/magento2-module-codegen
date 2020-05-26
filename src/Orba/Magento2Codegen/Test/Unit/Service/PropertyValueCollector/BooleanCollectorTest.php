<?php

namespace Orba\Magento2Codegen\Test\Unit\Service\PropertyValueCollector;

use InvalidArgumentException;
use Orba\Magento2Codegen\Model\ConstProperty;
use Orba\Magento2Codegen\Model\BooleanProperty;
use Orba\Magento2Codegen\Service\IO;
use Orba\Magento2Codegen\Service\PropertyValueCollector\BooleanCollector;
use Orba\Magento2Codegen\Test\Unit\TestCase;
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

    public function setUp(): void
    {
        $this->ioInstnaceMock = $this->getMockBuilder(SymfonyStyle::class)
            ->disableOriginalConstructor()->getMock();
        $this->ioMock = $this->getMockBuilder(IO::class)->disableOriginalConstructor()->getMock();
        $this->ioMock->expects($this->any())->method('getInstance')->willReturn($this->ioInstnaceMock);
        $this->booleanCollector = new BooleanCollector($this->ioMock);
    }

    public function testCollectValueThrowsExceptionIfPropertyIsNotBooleanProperty()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->booleanCollector->collectValue(
            $this->getMockBuilder(ConstProperty::class)->disableOriginalConstructor()->getMock()
        );
    }

    public function testCollectValueReturnsValueFromInputIfPropertyIsBooleanProperty()
    {
        $this->ioInstnaceMock->expects($this->once())->method('confirm')->willReturn(true);
        $result = $this->booleanCollector->collectValue(
            $this->getMockBuilder(BooleanProperty::class)->disableOriginalConstructor()->getMock()
        );
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
