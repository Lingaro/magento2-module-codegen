<?php

namespace Orba\Magento2Codegen\Test\Unit\Service;

use Orba\Magento2Codegen\Service\IO;
use Orba\Magento2Codegen\Test\Unit\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use RuntimeException;
use Symfony\Component\Console\Formatter\OutputFormatter;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class IOTest extends TestCase
{
    /**
     * @var IO
     */
    private $io;

    public function setUp(): void
    {
        $this->io = new IO();
    }

    public function testGetInstanceThrowsExceptionIfObjectNotInitialized(): void
    {
        $this->expectException(RuntimeException::class);
        $this->io->getInstance();
    }

    public function testGetInstanceReturnsInstanceIfObjectInitialized(): void
    {
        $this->init();
        $result = $this->io->getInstance();
        $this->assertInstanceOf(SymfonyStyle::class, $result);
    }

    public function testGetInputThrowsExceptionIfObjectNotInitialized(): void
    {
        $this->expectException(RuntimeException::class);
        $this->io->getInput();
    }

    public function testGetInputReturnsInputIfObjectInitialized(): void
    {
        $this->init();
        $result = $this->io->getInput();
        $this->assertInstanceOf(InputInterface::class, $result);
    }

    public function testGetOutputThrowsExceptionIfObjectNotInitialized(): void
    {
        $this->expectException(RuntimeException::class);
        $this->io->getOutput();
    }

    public function testGetOutputReturnsOutputIfObjectInitialized(): void
    {
        $this->init();
        $result = $this->io->getOutput();
        $this->assertInstanceOf(OutputInterface::class, $result);
    }

    public function testInitThrowsExceptionIfRunTwoTimes(): void
    {
        $this->expectException(RuntimeException::class);
        $this->init();
        $this->init();
    }

    private function init(): void
    {
        /** @var MockObject|InputInterface $inputMock */
        $inputMock = $this->getMockBuilder(InputInterface::class)->getMockForAbstractClass();
        $outputFormatterMock = $this->getMockBuilder(OutputFormatter::class)->disableOriginalConstructor()
            ->getMock();
        /** @var MockObject|OutputInterface $outputMock */
        $outputMock = $this->getMockBuilder(OutputInterface::class)->getMockForAbstractClass();
        $outputMock->expects($this->any())->method('getFormatter')->willReturn($outputFormatterMock);
        $this->io->init($inputMock, $outputMock);
    }
}