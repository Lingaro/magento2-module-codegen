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

    /**
     * @dataProvider additionProvider
     */
    public function testCollectValueReturnsValueFromInputIfPropertyIsBooleanProperty($inputValue)
    {
        $this->ioInstnaceMock->expects($this->once())->method('ask')->willReturn($inputValue);
        $result = $this->booleanCollector->collectValue(
            new BooleanProperty('foo_name')
        );
        static::assertSame(BooleanProperty::convertToBool($inputValue), $result);
    }

    public function additionProvider()
    {
        return [
            ['foo'],
            [true],
            [false],
            ['yes'],
            ['no'],
            [1],
            [0],
            ['1'],
            ['0']
        ];
    }
}
