<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Orba\Magento2Codegen\Test\Unit\Service\CommandUtil;

use Orba\Magento2Codegen\Command\Template\GenerateCommand;
use Orba\Magento2Codegen\Model\Template as TemplateModel;
use Orba\Magento2Codegen\Service\CommandUtil\PropertyCollector;
use Orba\Magento2Codegen\Service\CommandUtil\Template;
use Orba\Magento2Codegen\Service\CommandUtil\TemplateProperty;
use Orba\Magento2Codegen\Service\IO;
use Orba\Magento2Codegen\Service\PropertyDependencyChecker;
use Orba\Magento2Codegen\Service\PropertyValueCollector\CollectorFactory;
use Orba\Magento2Codegen\Service\PropertyBagFactory;
use Orba\Magento2Codegen\Service\TemplateProcessorInterface;
use Orba\Magento2Codegen\Test\Unit\TestCase;
use Orba\Magento2Codegen\Util\PropertyBag;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class TemplateTest extends TestCase
{
    private Template $template;

    /**
     * @var MockObject|PropertyBagFactory
     */
    private $propertyBagFactoryMock;

    /**
     * @var MockObject|IO
     */
    private $ioMock;

    /**
     * @var MockObject|TemplateProperty
     */
    private $templatePropertyUtilMock;

    /**
     * @var MockObject|CollectorFactory
     */
    private $propertyValueCollectorFactoryMock;

    /**
     * @var MockObject|TemplateProcessorInterface
     */
    private $templateProcessorMock;

    /**
     * @var MockObject|PropertyDependencyChecker
     */
    private $propertyDependencyChecker;

    public function setUp(): void
    {
        $this->propertyBagFactoryMock = $this->getMockBuilder(PropertyBagFactory::class)
            ->disableOriginalConstructor()->getMock();
        $this->ioMock = $this->getMockBuilder(IO::class)
            ->disableOriginalConstructor()->getMock();
        $this->templatePropertyUtilMock = $this->getMockBuilder(TemplateProperty::class)
            ->disableOriginalConstructor()->getMock();
        $this->propertyValueCollectorFactoryMock = $this->getMockBuilder(CollectorFactory::class)
            ->disableOriginalConstructor()->getMock();
        $this->templateProcessorMock = $this->getMockBuilder(TemplateProcessorInterface::class)
            ->getMockForAbstractClass();
        $this->propertyDependencyChecker = $this->getMockBuilder(PropertyDependencyChecker::class)
            ->disableOriginalConstructor()->getMock();
        $this->propertyCollectorMock = $this->getMockBuilder(PropertyCollector::class)
            ->disableOriginalConstructor()->getMock();
        $this->template = new Template(
            $this->propertyBagFactoryMock,
            $this->ioMock,
            $this->templatePropertyUtilMock,
            $this->propertyValueCollectorFactoryMock,
            $this->templateProcessorMock,
            $this->propertyDependencyChecker,
            $this->propertyCollectorMock
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

    public function testPreparePropertiesReturnsPropertyBagIfBasePropertyBagWasNotSet(): void
    {
        $result = $this->template->prepareProperties(new TemplateModel());
        $this->assertInstanceOf(PropertyBag::class, $result);
    }

    public function testPreparePropertiesReturnsTheSamePropertyBagObjectThatWasSet(): void
    {
        $propertyBag = new PropertyBag();
        $propertyBag['foo'] = 'bar';
        $result = $this->template->prepareProperties(new TemplateModel(), $propertyBag);
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
