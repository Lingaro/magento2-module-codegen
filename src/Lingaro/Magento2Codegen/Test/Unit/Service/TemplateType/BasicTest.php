<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Lingaro\Magento2Codegen\Test\Unit\Service\TemplateType;

use Lingaro\Magento2Codegen\Model\Template;
use Lingaro\Magento2Codegen\Service\PropertyBagFactory;
use Lingaro\Magento2Codegen\Service\TemplateType\Basic;
use Lingaro\Magento2Codegen\Test\Unit\TestCase;
use Lingaro\Magento2Codegen\Util\PropertyBag;
use PHPUnit\Framework\MockObject\MockObject;

class BasicTest extends TestCase
{
    private Basic $templateType;

    /**
     * @var MockObject|PropertyBagFactory
     */
    private $propertyBagFactoryMock;

    public function setUp(): void
    {
        $this->propertyBagFactoryMock = $this->getMockBuilder(PropertyBagFactory::class)
            ->disableOriginalConstructor()->getMock();
        $this->templateType = new Basic($this->propertyBagFactoryMock);
    }

    public function testBeforeGenerationCommandReturnsTrue(): void
    {
        $result = $this->templateType->beforeGenerationCommand(new Template());
        $this->assertTrue($result);
    }

    public function testGetBasePropertyBagReturnsPropertyBag(): void
    {
        $result = $this->templateType->getBasePropertyBag();
        $this->assertInstanceOf(PropertyBag::class, $result);
    }
}
