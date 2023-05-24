<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Orba\Magento2Codegen\Test\Unit\Service\CommandUtil;

use Orba\Magento2Codegen\Service\CodeGenerator;
use Orba\Magento2Codegen\Service\CommandUtil\Module;
use Orba\Magento2Codegen\Service\CommandUtil\Template;
use Orba\Magento2Codegen\Service\FilepathUtil;
use Orba\Magento2Codegen\Service\IO;
use Orba\Magento2Codegen\Service\Magento\Detector;
use Orba\Magento2Codegen\Service\PropertyBagFactory;
use Orba\Magento2Codegen\Test\Unit\TestCase;
use Orba\Magento2Codegen\Util\PropertyBag;
use PHPUnit\Framework\MockObject\MockObject;
use RuntimeException;
use Symfony\Component\Console\Style\SymfonyStyle;

class ModuleTest extends TestCase
{
    private Module $module;

    /**
     * @var MockObject|FilepathUtil
     */
    private $filepathUtilMock;

    /**
     * @var MockObject|PropertyBagFactory
     */
    private $propertyBagFactoryMock;

    /**
     * @var MockObject|IO
     */
    private $ioMock;

    /**
     * @var MockObject|CodeGenerator
     */
    private $codeGeneratorMock;

    /**
     * @var MockObject|Template
     */
    private $templateCommandUtilMock;

    /**
     * @var MockObject|Detector
     */
    private $detectorMock;

    public function setUp(): void
    {
        $this->filepathUtilMock = $this->getMockBuilder(FilepathUtil::class)
            ->disableOriginalConstructor()->getMock();
        $this->propertyBagFactoryMock = $this->getMockBuilder(PropertyBagFactory::class)
            ->disableOriginalConstructor()->getMock();
        $this->ioMock = $this->getMockBuilder(IO::class)->disableOriginalConstructor()->getMock();
        $this->codeGeneratorMock = $this->getMockBuilder(CodeGenerator::class)
            ->disableOriginalConstructor()->getMock();
        $this->templateCommandUtilMock = $this->getMockBuilder(Template::class)
            ->disableOriginalConstructor()->getMock();
        $this->detectorMock = $this->getMockBuilder(Detector::class)->disableOriginalConstructor()->getMock();
        $this->module = new Module(
            $this->filepathUtilMock,
            $this->propertyBagFactoryMock,
            $this->ioMock,
            $this->codeGeneratorMock,
            $this->templateCommandUtilMock,
            $this->detectorMock
        );
    }

    public function testGetPropertyBagReturnsEmptyBagIfModuleDataNotFoundInRegistrationFile(): void
    {
        $this->filepathUtilMock->expects($this->once())->method('getContent')
            ->willReturn('broken file with not module data');
        $result = $this->module->getPropertyBag();
        $this->assertFalse(isset($result['vendorName']));
        $this->assertFalse(isset($result['moduleName']));
    }

    public function testGetPropertyBagReturnsProperlyFilledBagIfModuleDataFoundInRegistrationFile(): void
    {
        $registrationFileContent = <<<PHP
<?php
\Magento\Framework\Component\ComponentRegistrar::register(
    \Magento\Framework\Component\ComponentRegistrar::MODULE,
    'Lingaro_Test',
    __DIR__
);
PHP;
        $this->filepathUtilMock->expects($this->once())->method('getContent')
            ->willReturn($registrationFileContent);
        $this->propertyBagFactoryMock->expects($this->once())->method('create')
            ->willReturn(new PropertyBag());
        $result = $this->module->getPropertyBag();
        $this->assertSame('Lingaro', $result['vendorName']);
        $this->assertSame('Test', $result['moduleName']);
    }

    public function testShouldCreateModuleReturnsFalseIfModuleExists(): void
    {
        $this->detectorMock->expects($this->once())->method('moduleExistsInDir')->willReturn(true);
        $result = $this->module->shouldCreateModule();
        $this->assertFalse($result);
    }

    public function testShouldCreateModuleReturnsFalseIfModuleDoesNotExistAndUserDoesNotWantToCreateOne(): void
    {
        $this->expectException(RuntimeException::class);
        $this->detectorMock->expects($this->once())->method('moduleExistsInDir')->willReturn(false);
        $ioInstanceMock = $this->getIoInstanceMock();
        $ioInstanceMock->expects($this->once())->method('confirm')->willReturn(false);
        $this->ioMock->expects($this->any())->method('getInstance')->willReturn($ioInstanceMock);
        $this->module->shouldCreateModule();
    }

    public function testShouldCreateModuleReturnsTrueIfModuleDoesNotExistAndUserWantToCreateOne(): void
    {
        $this->detectorMock->expects($this->once())->method('moduleExistsInDir')->willReturn(false);
        $ioInstanceMock = $this->getIoInstanceMock();
        $ioInstanceMock->expects($this->once())->method('confirm')->willReturn(true);
        $this->ioMock->expects($this->any())->method('getInstance')->willReturn($ioInstanceMock);
        $result = $this->module->shouldCreateModule();
        $this->assertTrue($result);
    }

    /**
     * @return MockObject|SymfonyStyle
     */
    private function getIoInstanceMock()
    {
        return $this->getMockBuilder(SymfonyStyle::class)->disableOriginalConstructor()->getMock();
    }
}
