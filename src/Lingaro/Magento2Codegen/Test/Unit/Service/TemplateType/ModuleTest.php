<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Lingaro\Magento2Codegen\Test\Unit\Service\TemplateType;

use Lingaro\Magento2Codegen\Model\Template;
use Lingaro\Magento2Codegen\Service\CommandUtil\Module as ModuleCommandUtil;
use Lingaro\Magento2Codegen\Service\TemplateFactory;
use Lingaro\Magento2Codegen\Service\TemplateType\Module;
use Lingaro\Magento2Codegen\Test\Unit\TestCase;
use Lingaro\Magento2Codegen\Util\PropertyBag;
use PHPUnit\Framework\MockObject\MockObject;
use RuntimeException;

class ModuleTest extends TestCase
{
    private Module $templateType;

    /**
     * @var MockObject|ModuleCommandUtil
     */
    private $commandUtilMock;

    public function setUp(): void
    {
        $this->commandUtilMock = $this->getMockBuilder(ModuleCommandUtil::class)
            ->disableOriginalConstructor()->getMock();
        $this->templateType = new Module($this->commandUtilMock);
    }

    public function testBeforeGenerationCommandThrowsExceptionIfTemplateFactoryNotSet(): void
    {
        $this->expectException(RuntimeException::class);
        $this->templateType->beforeGenerationCommand(new Template());
    }

    public function testBeforeGenerationCommandReturnsTrueIfTemplateFactoryIsSet(): void
    {
        /** @var MockObject|TemplateFactory $templateFactoryMock */
        $templateFactoryMock = $this->getMockBuilder(TemplateFactory::class)
            ->disableOriginalConstructor()->getMock();
        $this->templateType->setTemplateFactory($templateFactoryMock);
        $result = $this->templateType->beforeGenerationCommand(new Template());
        $this->assertTrue($result);
    }

    public function testGetBasePropertyBagReturnsPropertyBag(): void
    {
        $result = $this->templateType->getBasePropertyBag();
        $this->assertInstanceOf(PropertyBag::class, $result);
    }
}
