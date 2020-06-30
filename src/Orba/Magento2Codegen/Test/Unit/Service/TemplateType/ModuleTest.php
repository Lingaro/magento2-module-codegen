<?php

namespace Orba\Magento2Codegen\Test\Unit\Service\TemplateType;

use Orba\Magento2Codegen\Model\Template;
use Orba\Magento2Codegen\Service\CommandUtil\Module as ModuleCommandUtil;
use Orba\Magento2Codegen\Service\TemplateFactory;
use Orba\Magento2Codegen\Service\TemplateType\Module;
use Orba\Magento2Codegen\Test\Unit\TestCase;
use Orba\Magento2Codegen\Util\PropertyBag;
use PHPUnit\Framework\MockObject\MockObject;
use RuntimeException;

class ModuleTest extends TestCase
{
    /**
     * @var Module
     */
    private $templateType;

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
