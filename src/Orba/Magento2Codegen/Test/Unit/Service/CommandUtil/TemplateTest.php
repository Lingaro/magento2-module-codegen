<?php

namespace Orba\Magento2Codegen\Test\Unit\Service\CommandUtil;

use Exception;
use InvalidArgumentException;
use Orba\Magento2Codegen\Helper\IO;
use Orba\Magento2Codegen\Service\CodeGenerator;
use Orba\Magento2Codegen\Service\CommandUtil\Module;
use Orba\Magento2Codegen\Service\CommandUtil\Template;
use Orba\Magento2Codegen\Service\CommandUtil\TemplateProperty;
use Orba\Magento2Codegen\Service\TemplateFile;
use Orba\Magento2Codegen\Service\TemplatePropertyBagFactory;
use Orba\Magento2Codegen\Util\TemplatePropertyBag;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\InputInterface;

class TemplateTest extends TestCase
{
    /**
     * @var Template
     */
    private $template;

    /**
     * @var MockObject|TemplateFile
     */
    private $templateFileMock;

    /**
     * @var MockObject|TemplateProperty
     */
    private $propertyUtilMock;

    /**
     * @var MockObject|TemplatePropertyBagFactory
     */
    private $propertyBagFactoryMock;

    /**
     * @var MockObject|Module
     */
    private $moduleUtilMock;

    /**
     * @var MockObject|CodeGenerator
     */
    private $codeGeneratorMock;

    public static function setUpBeforeClass(): void
    {
        if (!defined('BP')) {
            define('BP', '/base/path');
        }
    }
    
    public function setUp(): void
    {
        $this->templateFileMock = $this->getMockBuilder(TemplateFile::class)
            ->disableOriginalConstructor()->getMock();
        $this->propertyUtilMock = $this->getMockBuilder(TemplateProperty::class)
            ->disableOriginalConstructor()->getMock();
        $this->propertyBagFactoryMock = $this->getMockBuilder(TemplatePropertyBagFactory::class)
            ->disableOriginalConstructor()->getMock();
        $this->moduleUtilMock = $this->getMockBuilder(Module::class)
            ->disableOriginalConstructor()->getMock();
        $this->codeGeneratorMock = $this->getMockBuilder(CodeGenerator::class)
            ->disableOriginalConstructor()->getMock();
        $this->template = new Template(
            $this->templateFileMock,
            $this->propertyUtilMock,
            $this->propertyBagFactoryMock,
            $this->moduleUtilMock,
            $this->codeGeneratorMock
        );
    }

    public function testGetTemplateNameReturnsInputArgumentIfItWasEarlierSpecified(): void
    {
        $ioMock = $this->getIoMock();
        $inputMock = $this->getInputMock();
        $inputMock->expects($this->once())->method('getArgument')->willReturn('old_value');
        $ioMock->expects($this->once())->method('getInput')->willReturn($inputMock);
        $result = $this->template->getTemplateName($ioMock);
        $this->assertSame('old_value', $result);
    }

    public function testGetTemplateNameAsksForValueAndReturnsItIfItWasNotEarlierSpecified(): void
    {
        $ioMock = $this->getIoMock();
        $inputMock = $this->getInputMock();
        $inputMock->expects($this->once())->method('getArgument')->willReturn(null);
        $ioMock->expects($this->once())->method('getInput')->willReturn($inputMock);
        $ioMock->expects($this->once())->method('ask')->willReturn('asked_value');
        $result = $this->template->getTemplateName($ioMock);
        $this->assertSame('asked_value', $result);
    }

    public function testValidateTemplateThrowsExceptionIfNameIsNull(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->template->validateTemplate(null);
    }

    public function testValidateTemplateThrowsExceptionIfNameIsEmpty(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->template->validateTemplate('');
    }

    public function testValidateTemplateThrowsExceptionIfTemplateFileDoesNotExist(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->templateFileMock->expects($this->once())->method('exists')->willReturn(false);
        $this->template->validateTemplate('template');
    }

    public function testValidateTemplateReturnsTrueIfTemplateFileExists(): void
    {
        $this->templateFileMock->expects($this->once())->method('exists')->willReturn(true);
        $result = $this->template->validateTemplate('template');
        $this->assertTrue($result);
    }

    public function testPreparePropertiesReturnsPropertyBagIfBasePropertyBagWasNotSet(): void
    {
        $result = $this->template->prepareProperties('template', $this->getIoMock());
        $this->assertInstanceOf(TemplatePropertyBag::class, $result);
    }

    public function testPreparePropertiesReturnsTheSamePropertyBagObjectThatWasSet(): void
    {
        $propertyBag = new TemplatePropertyBag();
        $propertyBag['foo'] = 'bar';
        $result = $this->template->prepareProperties('template', $this->getIoMock(), $propertyBag);
        $this->assertSame('bar', $result['foo']);
    }

    public function testShouldCreateModuleReturnsFalseIfModuleExists(): void
    {
        $this->moduleUtilMock->expects($this->once())->method('exists')->willReturn(true);
        $result = $this->template->shouldCreateModule('template', $this->getIoMock());
        $this->assertFalse($result);
    }

    public function testShouldCreateModuleReturnsFalseIfModuleDoesNotExistAndTemplateIsAGlobalPackage(): void
    {
        $this->moduleUtilMock->expects($this->once())->method('exists')->willReturn(false);
        $result = $this->template->shouldCreateModule('module', $this->getIoMock());
        $this->assertFalse($result);
    }

    public function testShouldCreateModuleReturnsFalseIfModuleDoesNotExistAndUserDoesNotWantToCreateOne(): void
    {
        $this->expectException(Exception::class);
        $this->moduleUtilMock->expects($this->once())->method('exists')->willReturn(false);
        $ioMock = $this->getIoMock();
        $ioMock->expects($this->once())->method('confirm')->willReturn(false);
        $this->template->shouldCreateModule('template', $ioMock);
    }

    public function testShouldCreateModuleReturnsTrueIfModuleDoesNotExistAndUserWantToCreateOne(): void
    {
        $this->moduleUtilMock->expects($this->once())->method('exists')->willReturn(false);
        $ioMock = $this->getIoMock();
        $ioMock->expects($this->once())->method('confirm')->willReturn(true);
        $result = $this->template->shouldCreateModule('template', $ioMock);
        $this->assertTrue($result);
    }

    public function testCreateModuleReturnsTheSamePropertyBagObjectThatWasSet(): void
    {
        $propertyBag = new TemplatePropertyBag();
        $propertyBag['foo'] = 'bar';
        $result = $this->template->createModule($propertyBag, $this->getIoMock());
        $this->assertSame('bar', $result['foo']);
    }

    public function testGetBasePropertyBagReturnsEmptyBagIfTemplateIsAGlobalPackage(): void
    {
        $result = $this->template->getBasePropertyBag('module', $this->getIoMock());
        $this->assertInstanceOf(TemplatePropertyBag::class, $result);
    }

    public function testGetBasePropertyBagReturnsBagTakenFromModuleIfTemplateIsNotAGlobalPackage(): void
    {
        $propertyBag = new TemplatePropertyBag();
        $propertyBag['foo'] = 'bar';
        $this->moduleUtilMock->expects($this->once())->method('getPropertyBag')->willReturn($propertyBag);
        $result = $this->template->getBasePropertyBag('template', $this->getIoMock());
        $this->assertSame('bar', $result['foo']);
    }

    /**
     * @return MockObject|IO
     */
    private function getIoMock()
    {
        return $this->getMockBuilder(IO::class)->disableOriginalConstructor()->getMock();
    }

    /**
     * @return MockObject|InputInterface
     */
    private function getInputMock()
    {
        return $this->getMockBuilder(InputInterface::class)->getMockForAbstractClass();
    }
}