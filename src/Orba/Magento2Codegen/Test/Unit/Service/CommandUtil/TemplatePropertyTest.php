<?php

namespace Orba\Magento2Codegen\Test\Unit\Service\CommandUtil;

use InvalidArgumentException;
use Orba\Magento2Codegen\Service\CommandUtil\TemplateProperty;
use Orba\Magento2Codegen\Service\IO;
use Orba\Magento2Codegen\Service\TemplateDir;
use Orba\Magento2Codegen\Service\TemplateFile;
use Orba\Magento2Codegen\Service\TemplateProcessorInterface;
use Orba\Magento2Codegen\Service\TemplatePropertyBagFactory;
use Orba\Magento2Codegen\Service\TemplatePropertyMerger;
use Orba\Magento2Codegen\Test\Unit\TestCase;
use Orba\Magento2Codegen\Util\TemplatePropertyBag;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\Yaml\Parser;

class TemplatePropertyTest extends TestCase
{
    /**
     * @var TemplateProperty
     */
    private $templateProperty;

    /**
     * @var MockObject|Parser
     */
    private $yamlParserMock;

    /**
     * @var MockObject|TemplateFile
     */
    private $templateFileMock;

    /**
     * @var MockObject|TemplateProcessorInterface
     */
    private $templateProcessorMock;

    /**
     * @var MockObject|TemplatePropertyMerger
     */
    private $templatePropertyMergerMock;

    /** @var MockObject|TemplateDir */
    private $templateDirMock;

    /** @var MockObject|TemplatePropertyBagFactory */
    private $propertyBagFactoryMock;

    /** @var MockObject|IO */
    private $ioMock;

    public function setUp(): void
    {
        $this->yamlParserMock = $this->getMockBuilder(Parser::class)->disableOriginalConstructor()->getMock();
        $this->templateFileMock = $this->getMockBuilder(TemplateFile::class)->disableOriginalConstructor()
            ->getMock();
        $this->templateProcessorMock = $this->getMockBuilder(TemplateProcessorInterface::class)
            ->getMockForAbstractClass();
        $this->templatePropertyMergerMock = $this->getMockBuilder(TemplatePropertyMerger::class)
            ->disableOriginalConstructor()->getMock();
        $this->templateDirMock = $this->getMockBuilder(TemplateDir::class)->disableOriginalConstructor()->getMock();
        $this->propertyBagFactoryMock = $this->getMockBuilder(TemplatePropertyBagFactory::class)
            ->disableOriginalConstructor()->getMock();
        $this->ioMock = $this->getMockBuilder(IO::class)
            ->disableOriginalConstructor()->getMock();
        $templateProperty = new TemplateProperty(
            $this->yamlParserMock,
            $this->templateFileMock,
            $this->templateProcessorMock,
            $this->templatePropertyMergerMock,
            $this->templateDirMock,
            $this->propertyBagFactoryMock,
            $this->ioMock
        );
        $this->templateProperty = $templateProperty->withTemplateName('templateName');
    }

    public function testPreparePropertiesThrowsExceptionIfParserResultIsNotAnArray(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->yamlParserMock->expects($this->once())->method('parseFile')->willReturn('not array');
        $this->templateProperty->prepareProperties();
    }

    public function testPreparePropertiesExceptionIfParserResultIsNotFlatArray(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->yamlParserMock->expects($this->once())->method('parseFile')
            ->willReturn(['not' => ['flat' => 'array']]);
        $this->templateProperty->prepareProperties();
    }

    public function testPreparePropertiesReturnsPropertyBagIfBasePropertyBagWasNotSet(): void
    {
        $this->yamlParserMock->expects($this->once())->method('parseFile')
            ->willReturn(['propertyName' => 'propertyValue']);
        $result = $this->templateProperty->prepareProperties();
        $this->assertInstanceOf(TemplatePropertyBag::class, $result);
    }

    public function testPreparePropertiesReturnsTheSamePropertyBagObjectThatWasSet(): void
    {
        $this->yamlParserMock->expects($this->once())->method('parseFile')
            ->willReturn([]);
        $propertyBag = new TemplatePropertyBag();
        $propertyBag['foo'] = 'bar';
        $result = $this->templateProperty->prepareProperties($propertyBag);
        $this->assertSame('bar', $result['foo']);
    }

    public function testPreparePropertiesReturnsPropertyBagWithScalarPropertyTakenFromTemplate(): void
    {
        $this->helperPreparePropertiesInTemplate();
        $this->templatePropertyMergerMock->expects($this->any())->method('merge')->willReturn(['foo' => null]);
        $propertyBag = new TemplatePropertyBag();
        $ioInstanceMock = $this->getIoInstanceMock();
        $ioInstanceMock->expects($this->once())->method('ask')->willReturn('bar');
        $this->ioMock->expects($this->any())->method('getInstance')->willReturn($ioInstanceMock);
        $result = $this->templateProperty->prepareProperties($propertyBag);
        $this->assertSame('bar', $result['foo']);
    }

    public function testPreparePropertiesReturnsPropertyBagWithOneItemOneElementArrayPropertyTakenFromTemplate(): void
    {
        $this->helperPreparePropertiesInTemplate();
        $this->templatePropertyMergerMock->expects($this->any())->method('merge')->willReturn(['foo' => ['bar']]);
        $propertyBag = new TemplatePropertyBag();
        $ioInstanceMock = $this->getIoInstanceMock();
        $ioInstanceMock->expects($this->once())->method('ask')->willReturn('moo');
        $ioInstanceMock->expects($this->once())->method('confirm')->willReturn(false);
        $this->ioMock->expects($this->any())->method('getInstance')->willReturn($ioInstanceMock);
        $result = $this->templateProperty->prepareProperties($propertyBag);
        $this->assertSame([['bar' => 'moo']], $result['foo']);
    }

    public function testPreparePropertiesReturnsPropertyBagWithOneItemTwoElementsArrayPropertyTakenFromTemplate(): void
    {
        $this->helperPreparePropertiesInTemplate();
        $this->templatePropertyMergerMock->expects($this->any())->method('merge')->willReturn(['foo' => ['bar', 'gar']]);
        $propertyBag = new TemplatePropertyBag();
        $ioInstanceMock = $this->getIoInstanceMock();
        $ioInstanceMock->expects($this->any())->method('ask')
            ->willReturnOnConsecutiveCalls('moo', 'goo');
        $ioInstanceMock->expects($this->once())->method('confirm')->willReturn(false);
        $this->ioMock->expects($this->any())->method('getInstance')->willReturn($ioInstanceMock);
        $result = $this->templateProperty->prepareProperties($propertyBag);
        $this->assertSame([['bar' => 'moo', 'gar' => 'goo']], $result['foo']);
    }

    public function testPreparePropertiesReturnsPropertyBagWithTwoItemOneElementArrayPropertyTakenFromTemplate(): void
    {
        $this->helperPreparePropertiesInTemplate();
        $this->templatePropertyMergerMock->expects($this->any())->method('merge')
            ->willReturn(['foo' => ['bar']]);
        $propertyBag = new TemplatePropertyBag();
        $ioInstanceMock = $this->getIoInstanceMock();
        $ioInstanceMock->expects($this->any())->method('ask')
            ->willReturnOnConsecutiveCalls('moo', 'goo');
        $ioInstanceMock->expects($this->any())->method('confirm')
            ->willReturnOnConsecutiveCalls(true, false);
        $this->ioMock->expects($this->any())->method('getInstance')->willReturn($ioInstanceMock);
        $result = $this->templateProperty->prepareProperties($propertyBag);
        $this->assertSame([['bar' => 'moo'], ['bar' => 'goo']], $result['foo']);
    }

    private function helperPreparePropertiesInTemplate()
    {
        $this->yamlParserMock->expects($this->once())->method('parseFile')
            ->willReturn([]);
        $splFileMock = $this->getMockBuilder(\Symfony\Component\Finder\SplFileInfo::class)
            ->disableOriginalConstructor()->getMock();
        $splFileMock->expects($this->once())->method('getPath')->willReturn('filePath');
        $splFileMock->expects($this->once())->method('getContents')->willReturn('fileContent');
        $this->templateFileMock->expects($this->once())->method('getTemplateFiles')
            ->willReturn([$splFileMock]);
        $this->templateProcessorMock->expects($this->any())->method('getPropertiesInText')->willReturn(['anything']);
    }
    /**
     * @return MockObject|SymfonyStyle
     */
    private function getIoInstanceMock()
    {
        return $this->getMockBuilder(SymfonyStyle::class)->disableOriginalConstructor()->getMock();
    }
}
