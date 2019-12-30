<?php

namespace Orba\Magento2Codegen\Test\Unit\Service\CommandUtil;

use InvalidArgumentException;
use Orba\Magento2Codegen\Service\CommandUtil\TemplateProperty;
use Orba\Magento2Codegen\Service\TemplateFile;
use Orba\Magento2Codegen\Service\TemplateProcessorInterface;
use Orba\Magento2Codegen\Test\Unit\TestCase;
use Orba\Magento2Codegen\Util\TemplatePropertyBag;
use PHPUnit\Framework\MockObject\MockObject;
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

    public function setUp(): void
    {
        $this->yamlParserMock = $this->getMockBuilder(Parser::class)->disableOriginalConstructor()->getMock();
        $this->templateFileMock = $this->getMockBuilder(TemplateFile::class)->disableOriginalConstructor()
            ->getMock();
        $this->templateProcessorMock = $this->getMockBuilder(TemplateProcessorInterface::class)
            ->getMockForAbstractClass();
        $this->templateProperty = new TemplateProperty(
            $this->yamlParserMock,
            $this->templateFileMock,
            $this->templateProcessorMock
        );
    }

    public function testGetAllPropertiesInTemplateReturnsEmptyArrayIfThereAreNoPropertiesInTemplateFiles(): void
    {
        $result = $this->templateProperty->getAllPropertiesInTemplate('template');
        $this->assertSame([], $result);
    }

    public function testGetAllPropertiesInTemplateReturnsUniqueArrayIfThereAreDuplicatedPropertiesInTemplateFiles(): void
    {
        $this->templateFileMock->expects($this->once())->method('getTemplateFiles')
            ->willReturn([$this->getFileMock()]);
        $this->templateProcessorMock->expects($this->exactly(2))->method('getPropertiesInText')
            ->willReturnOnConsecutiveCalls(['foo', 'bar'], ['moo', 'bar']);
        $result = $this->templateProperty->getAllPropertiesInTemplate('template');
        $this->assertCount(3, $result);
        foreach ($result as $value) {
            $this->assertTrue(in_array($value, ['foo', 'bar', 'moo']));
        }
    }

    public function testAddPropertiesAddsPropertiesToPropertyBag(): void
    {
        $propertyBag = new TemplatePropertyBag();
        $this->templateProperty->addProperties($propertyBag, ['foo' => 'bar']);
        $this->assertSame('bar', $propertyBag['foo']);
    }

    public function testGetPropertiesFromYamlFileThrowsExceptionIfParserResultIsNotAnArray(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->yamlParserMock->expects($this->once())->method('parseFile')->willReturn('not array');
        $this->templateProperty->getPropertiesFromYamlFile('filepath');
    }

    public function testGetPropertiesFromYamlFileThrowsExceptionIfParserResultIsNotFlatArray(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->yamlParserMock->expects($this->once())->method('parseFile')
            ->willReturn(['not' => ['flat' => 'array']]);
        $this->templateProperty->getPropertiesFromYamlFile('filepath');
    }

    /**
     * @return MockObject|SplFileInfo
     */
    private function getFileMock()
    {
        $fileMock = $this->getMockBuilder(SplFileInfo::class)->disableOriginalConstructor()->getMock();
        $fileMock->expects($this->any())->method('getPath')->willReturn('path');
        $fileMock->expects($this->any())->method('getContents')->willReturn('contents');
        return $fileMock;
    }
}