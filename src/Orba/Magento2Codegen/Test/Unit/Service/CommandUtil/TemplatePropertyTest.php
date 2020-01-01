<?php

namespace Orba\Magento2Codegen\Test\Unit\Service\CommandUtil;

use InvalidArgumentException;
use Orba\Magento2Codegen\Service\CommandUtil\TemplateProperty;
use Orba\Magento2Codegen\Service\TemplateFile;
use Orba\Magento2Codegen\Service\TemplateProcessorInterface;
use Orba\Magento2Codegen\Service\TemplatePropertyMerger;
use Orba\Magento2Codegen\Test\Unit\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
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

    public function setUp(): void
    {
        $this->yamlParserMock = $this->getMockBuilder(Parser::class)->disableOriginalConstructor()->getMock();
        $this->templateFileMock = $this->getMockBuilder(TemplateFile::class)->disableOriginalConstructor()
            ->getMock();
        $this->templateProcessorMock = $this->getMockBuilder(TemplateProcessorInterface::class)
            ->getMockForAbstractClass();
        $this->templatePropertyMergerMock = $this->getMockBuilder(TemplatePropertyMerger::class)
            ->disableOriginalConstructor()->getMock();
        $this->templateProperty = new TemplateProperty(
            $this->yamlParserMock,
            $this->templateFileMock,
            $this->templateProcessorMock,
            $this->templatePropertyMergerMock
        );
    }

    public function testGetAllPropertiesInTemplateReturnsEmptyArrayIfThereAreNoPropertiesInTemplateFiles(): void
    {
        $result = $this->templateProperty->getAllPropertiesInTemplate('template');
        $this->assertSame([], $result);
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
}