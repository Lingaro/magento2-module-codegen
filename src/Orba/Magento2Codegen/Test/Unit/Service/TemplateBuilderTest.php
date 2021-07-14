<?php

/**
 * @copyright Copyright © 2021 Orba. All rights reserved.
 * @author    info@orba.co
 */

declare(strict_types=1);

namespace Orba\Magento2Codegen\Test\Unit\Service;

use InvalidArgumentException;
use Orba\Magento2Codegen\Model\Template;
use Orba\Magento2Codegen\Service\TemplateBuilder;
use Orba\Magento2Codegen\Service\TemplateFactory;
use Orba\Magento2Codegen\Service\TemplateFile;
use Orba\Magento2Codegen\Service\TemplateType\TypeInterface;
use Orba\Magento2Codegen\Test\Unit\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use RuntimeException;

/**
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
class TemplateBuilderTest extends TestCase
{
    private TemplateBuilder $templateBuilder;
    private Template $templateModel;

    /**
     * @var MockObject|TemplateFile
     */
    private $templateFileMock;

    /**
     * @var MockObject[]|TypeInterface[]
     */
    private $templateTypesMock;

    public function setUp(): void
    {
        $this->templateFileMock = $this->getMockBuilder(TemplateFile::class)
            ->disableOriginalConstructor()->getMock();
        $this->templateTypesMock = [
            'basic' => $this->getMockBuilder(TypeInterface::class)->getMockForAbstractClass()
        ];
        $this->templateModel = new Template();
        $this->templateBuilder = new TemplateBuilder($this->templateFileMock, $this->templateTypesMock);
    }

    public function testAddNameReturnsSelf(): void
    {
        $result = $this->templateBuilder->addName($this->templateModel, 'foo');
        $this->assertSame($this->templateBuilder, $result);
    }

    public function testAddDescriptionThrowsExceptionIfNameIsNotSet(): void
    {
        $this->expectException(RuntimeException::class);
        $this->templateBuilder->addDescription($this->templateModel);
    }

    public function testAddDescriptionReturnsSelfIfNameIsSet(): void
    {
        $result = $this->templateBuilder->addDescription($this->templateModel->setName('foo'));
        $this->assertSame($this->templateBuilder, $result);
    }

    public function testAddPropertiesConfigThrowsExceptionIfNameIsNotSet(): void
    {
        $this->expectException(RuntimeException::class);
        $this->templateBuilder->addPropertiesConfig($this->templateModel);
    }

    public function testAddPropertiesConfigReturnsSelfIfNameIsSet(): void
    {
        $result = $this->templateBuilder->addPropertiesConfig($this->templateModel->setName('foo'));
        $this->assertSame($this->templateBuilder, $result);
    }

    public function testAddAfterGenerateConfigThrowsExceptionIfNameIsNotSet(): void
    {
        $this->expectException(RuntimeException::class);
        $this->templateBuilder->addAfterGenerateConfig($this->templateModel);
    }

    public function testAddAfterGenerateConfigReturnsSelfIfNameIsSet(): void
    {
        $result = $this->templateBuilder->addAfterGenerateConfig($this->templateModel->setName('foo'));
        $this->assertSame($this->templateBuilder, $result);
    }

    public function testAddDependenciesThrowsExceptionIfNameIsNotSet(): void
    {
        $this->expectException(RuntimeException::class);
        $this->templateBuilder->addDependencies($this->templateModel, $this->getTemplateFactoryMock());
    }

    public function testAddDependenciesThrowsExceptionIfDependenciesArrayIsInvalid(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->templateFileMock->expects($this->once())->method('getDependencies')
            ->willReturn([['not scalar']]);
        $this->templateBuilder
            ->addDependencies($this->templateModel->setName('foo'), $this->getTemplateFactoryMock());
    }

    public function testAddDependenciesReturnsSelfIfNameIsSetAndDependencyArrayIsValid(): void
    {
        $this->templateFileMock->expects($this->once())->method('getDependencies')
            ->willReturn(['bar', 'baz']);
        $result = $this->templateBuilder
            ->addDependencies($this->templateModel->setName('foo'), $this->getTemplateFactoryMock());
        $this->assertSame($this->templateBuilder, $result);
    }

    public function testAddTypeServiceThrowsExceptionIfNameIsNotSet(): void
    {
        $this->expectException(RuntimeException::class);
        $this->templateBuilder->addTypeService($this->templateModel);
    }

    public function testAddTypeServiceThrowsExceptionIfTypeIsNotSet(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->templateFileMock->expects($this->once())->method('getType')->willReturn('');
        $this->templateBuilder->addTypeService($this->templateModel->setName('foo'));
    }

    public function testAddTypeServiceThrowsExceptionIfTypeNotFound(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->templateFileMock->expects($this->once())->method('getType')->willReturn('non-existent');
        $this->templateBuilder->addTypeService($this->templateModel->setName('foo'));
    }

    public function testAddTypeServiceReturnsSelfIfNameIsSetAndTypeExists(): void
    {
        $this->templateFileMock->expects($this->once())->method('getType')->willReturn('basic');
        $result = $this->templateBuilder->addTypeService($this->templateModel->setName('foo'));
        $this->assertSame($this->templateBuilder, $result);
    }

    public function testAddIsAbstractServiceReturnsSelf(): void
    {
        $this->templateFileMock->expects($this->once())->method('getIsAbstract')->willReturn(true);
        $result = $this->templateBuilder->addIsAbstract($this->templateModel->setName('foo'));
        $this->assertSame($this->templateBuilder, $result);
    }

    /**
     * @return MockObject|TemplateFactory
     */
    private function getTemplateFactoryMock(): MockObject
    {
        return $this->getMockBuilder(TemplateFactory::class)->disableOriginalConstructor()->getMock();
    }
}
