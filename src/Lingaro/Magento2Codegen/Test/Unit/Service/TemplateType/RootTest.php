<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Orba\Magento2Codegen\Test\Unit\Service\TemplateType;

use Orba\Magento2Codegen\Model\Template;
use Orba\Magento2Codegen\Service\CommandUtil\Root as RootCommandUtil;
use Orba\Magento2Codegen\Service\PropertyBagFactory;
use Orba\Magento2Codegen\Service\TemplateType\Root;
use Orba\Magento2Codegen\Test\Unit\TestCase;
use Orba\Magento2Codegen\Util\PropertyBag;
use PHPUnit\Framework\MockObject\MockObject;
use RuntimeException;

class RootTest extends TestCase
{
    private Root $templateType;

    /**
     * @var MockObject|PropertyBagFactory
     */
    private $propertyBagFactoryMock;

    /**
     * @var MockObject|RootCommandUtil
     */
    private $commandUtilMock;

    public function setUp(): void
    {
        $this->propertyBagFactoryMock = $this->getMockBuilder(PropertyBagFactory::class)
            ->disableOriginalConstructor()->getMock();
        $this->commandUtilMock = $this->getMockBuilder(RootCommandUtil::class)
            ->disableOriginalConstructor()->getMock();
        $this->templateType = new Root($this->propertyBagFactoryMock, $this->commandUtilMock);
    }

    public function testBeforeGenerationCommandThrowsExceptionIfCurrentDirIsNotMagentoRoot(): void
    {
        $this->expectException(RuntimeException::class);
        $this->commandUtilMock->expects($this->once())->method('isCurrentDirMagentoRoot')
            ->willReturn(false);
        $this->templateType->beforeGenerationCommand(new Template());
    }

    public function testBeforeGenerationCommandReturnsTrueIfCurrentDirIsMagentoRoot(): void
    {
        $this->commandUtilMock->expects($this->once())->method('isCurrentDirMagentoRoot')
            ->willReturn(true);
        $result = $this->templateType->beforeGenerationCommand(new Template());
        $this->assertTrue($result);
    }

    public function testGetBasePropertyBagReturnsPropertyBag(): void
    {
        $result = $this->templateType->getBasePropertyBag();
        $this->assertInstanceOf(PropertyBag::class, $result);
    }
}
