<?php

namespace Orba\Magento2Codegen\Test\Unit\Service\CommandUtil;

use Exception;
use InvalidArgumentException;
use Orba\Magento2Codegen\Command\Template\GenerateCommand;
use Orba\Magento2Codegen\Service\CodeGenerator;
use Orba\Magento2Codegen\Service\CommandUtil\Module;
use Orba\Magento2Codegen\Service\CommandUtil\Template;
use Orba\Magento2Codegen\Service\CommandUtil\TemplateProperty;
use Orba\Magento2Codegen\Service\IO;
use Orba\Magento2Codegen\Service\PropertyValueCollector\CollectorFactory;
use Orba\Magento2Codegen\Service\TemplateFile;
use Orba\Magento2Codegen\Service\PropertyBagFactory;
use Orba\Magento2Codegen\Test\Unit\TestCase;
use Orba\Magento2Codegen\Util\PropertyBag;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

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
     * @var MockObject|PropertyBagFactory
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

    /**
     * @var MockObject|IO
     */
    private $ioMock;

    /**
     * @var MockObject|TemplateProperty
     */
    private $templatePropertyUtilMock;

    /**
     * @var MockObject
     */
    private $propertyValueCollectorFactoryMock;

    public function setUp(): void
    {
        $this->templateFileMock = $this->getMockBuilder(TemplateFile::class)
            ->disableOriginalConstructor()->getMock();
        $this->propertyBagFactoryMock = $this->getMockBuilder(PropertyBagFactory::class)
            ->disableOriginalConstructor()->getMock();
        $this->moduleUtilMock = $this->getMockBuilder(Module::class)
            ->disableOriginalConstructor()->getMock();
        $this->codeGeneratorMock = $this->getMockBuilder(CodeGenerator::class)
            ->disableOriginalConstructor()->getMock();
        $this->ioMock = $this->getMockBuilder(IO::class)
            ->disableOriginalConstructor()->getMock();
        $this->templatePropertyUtilMock = $this->getMockBuilder(TemplateProperty::class)
            ->disableOriginalConstructor()->getMock();
        $this->propertyValueCollectorFactoryMock = $this->getMockBuilder(CollectorFactory::class)
            ->disableOriginalConstructor()->getMock();
        $this->template = new Template(
            $this->templateFileMock,
            $this->propertyBagFactoryMock,
            $this->moduleUtilMock,
            $this->codeGeneratorMock,
            $this->ioMock,
            $this->templatePropertyUtilMock,
            $this->propertyValueCollectorFactoryMock
        );
    }

    public function testGetTemplateNameReturnsInputArgumentIfItWasEarlierSpecified(): void
    {
        $inputMock = $this->getInputMock();
        $inputMock->expects($this->once())->method('getArgument')->willReturn('old_value');
        $this->ioMock->expects($this->once())->method('getInput')->willReturn($inputMock);
        $result = $this->template->getTemplateName();
        $this->assertSame('old_value', $result);
    }

    public function testGetTemplateNameAsksForValueAndReturnsItIfItWasNotEarlierSpecified(): void
    {
        $inputMock = $this->getInputMock();
        $inputMock->expects($this->once())->method('getArgument')->willReturn(null);
        $this->ioMock->expects($this->any())->method('getInput')->willReturn($inputMock);
        $ioInstanceMock = $this->getIoInstanceMock();
        $ioInstanceMock->expects($this->once())->method('ask')->willReturn('asked_value');
        $this->ioMock->expects($this->any())->method('getInstance')->willReturn($ioInstanceMock);
        $result = $this->template->getTemplateName();
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

    public function testShouldCreateModuleReturnsFalseIfModuleExists(): void
    {
        $this->ioMock->expects($this->once())->method('getInput')->willReturn($this->getInputMock());
        $this->moduleUtilMock->expects($this->once())->method('exists')->willReturn(true);
        $result = $this->template->shouldCreateModule('template');
        $this->assertFalse($result);
    }

    public function testShouldCreateModuleReturnsFalseIfModuleDoesNotExistAndTemplateIsAGlobalPackage(): void
    {
        $this->ioMock->expects($this->once())->method('getInput')->willReturn($this->getInputMock());
        $this->moduleUtilMock->expects($this->once())->method('exists')->willReturn(false);
        $result = $this->template->shouldCreateModule('module');
        $this->assertFalse($result);
    }

    public function testShouldCreateModuleReturnsFalseIfModuleDoesNotExistAndUserDoesNotWantToCreateOne(): void
    {
        $this->expectException(Exception::class);
        $this->ioMock->expects($this->once())->method('getInput')->willReturn($this->getInputMock());
        $this->moduleUtilMock->expects($this->once())->method('exists')->willReturn(false);
        $ioInstanceMock = $this->getIoInstanceMock();
        $ioInstanceMock->expects($this->once())->method('confirm')->willReturn(false);
        $this->ioMock->expects($this->any())->method('getInstance')->willReturn($ioInstanceMock);
        $this->template->shouldCreateModule('template');
    }

    public function testShouldCreateModuleReturnsTrueIfModuleDoesNotExistAndUserWantToCreateOne(): void
    {
        $this->moduleUtilMock->expects($this->once())->method('exists')->willReturn(false);
        $ioInstanceMock = $this->getIoInstanceMock();
        $ioInstanceMock->expects($this->once())->method('confirm')->willReturn(true);
        $this->ioMock->expects($this->any())->method('getInstance')->willReturn($ioInstanceMock);
        $this->ioMock->expects($this->once())->method('getInput')->willReturn($this->getInputMock());
        $result = $this->template->shouldCreateModule('template');
        $this->assertTrue($result);
    }

    public function testCreateModuleReturnsTheSamePropertyBagObjectThatWasSet(): void
    {
        $propertyBag = new PropertyBag();
        $propertyBag['foo'] = 'bar';
        $result = $this->template->createModule($propertyBag);
        $this->assertSame('bar', $result['foo']);
    }

    public function testGetBasePropertyBagReturnsEmptyBagIfTemplateIsAGlobalPackage(): void
    {
        $result = $this->template->getBasePropertyBag('module');
        $this->assertInstanceOf(PropertyBag::class, $result);
    }

    public function testGetBasePropertyBagReturnsBagTakenFromModuleIfTemplateIsNotAGlobalPackage(): void
    {
        $propertyBag = new PropertyBag();
        $propertyBag['foo'] = 'bar';
        $this->ioMock->expects($this->once())->method('getInput')->willReturn($this->getInputMock());
        $this->moduleUtilMock->expects($this->once())->method('getPropertyBag')->willReturn($propertyBag);
        $result = $this->template->getBasePropertyBag('template');
        $this->assertSame('bar', $result['foo']);
    }

    public function testPreparePropertiesReturnsPropertyBagIfBasePropertyBagWasNotSet(): void
    {
        $result = $this->template->prepareProperties('template');
        $this->assertInstanceOf(PropertyBag::class, $result);
    }

    public function testPreparePropertiesReturnsTheSamePropertyBagObjectThatWasSet(): void
    {
        $propertyBag = new PropertyBag();
        $propertyBag['foo'] = 'bar';
        $result = $this->template->prepareProperties('template', $propertyBag);
        $this->assertSame('bar', $result['foo']);
    }

    /**
     * @return MockObject|InputInterface
     */
    private function getInputMock()
    {
        $inputMock = $this->getMockBuilder(InputInterface::class)->getMockForAbstractClass();
        $inputMock->expects($this->any())->method('getOption')
            ->with(GenerateCommand::OPTION_ROOT_DIR)->willReturn('/root');
        return $inputMock;
    }

    /**
     * @return MockObject|SymfonyStyle
     */
    private function getIoInstanceMock()
    {
        return $this->getMockBuilder(SymfonyStyle::class)->disableOriginalConstructor()->getMock();
    }
}