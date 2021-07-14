<?php

/**
 * @copyright Copyright © 2021 Orba. All rights reserved.
 * @author    info@orba.co
 */

declare(strict_types=1);

namespace Orba\Magento2Codegen\Test\Unit\Service\CommandUtil;

use Orba\Magento2Codegen\Model\PropertyInterface;
use Orba\Magento2Codegen\Model\Template;
use Orba\Magento2Codegen\Service\CommandUtil\TemplateProperty;
use Orba\Magento2Codegen\Service\Config;
use Orba\Magento2Codegen\Service\PropertyFactory;
use Orba\Magento2Codegen\Test\Unit\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class TemplatePropertyTest extends TestCase
{
    private TemplateProperty $templateProperty;

    /**
     * @var MockObject|Config
     */
    private $configMock;

    /**
     * @var MockObject|PropertyFactory
     */
    private $propertyFactoryMock;

    public function setUp(): void
    {
        $this->configMock = $this->getMockBuilder(Config::class)->disableOriginalConstructor()->getMock();
        $this->propertyFactoryMock = $this->getMockBuilder(PropertyFactory::class)
            ->disableOriginalConstructor()->getMock();
        $this->templateProperty = new TemplateProperty(
            $this->configMock,
            $this->propertyFactoryMock
        );
    }

    public function testCollectConstPropertiesReturnsEmptyArrayIfDefaultPropertiesConfigIsEmpty(): void
    {
        $this->configMock->expects($this->once())->method('offsetGet')->willReturn([]);
        $result = $this->templateProperty->collectConstProperties();
        $this->assertSame([], $result);
    }

    public function testCollectConstPropertiesReturnsArrayWithPropertyIfDefaultPropertiesConfigIsNotEmpty(): void
    {
        $this->configMock->expects($this->once())->method('offsetGet')
            ->willReturn([['name' => 'foo', 'value' => 'bar']]);
        $result = $this->templateProperty->collectConstProperties();
        $this->assertCount(1, $result);
        $this->assertContainsOnlyInstancesOf(PropertyInterface::class, $result);
    }

    public function testCollectInputPropertiesReturnsEmptyArrayIfTemplatePropertiesConfigIsEmpty(): void
    {
        $result = $this->templateProperty->collectInputProperties(new Template());
        $this->assertSame([], $result);
    }

    public function testCollectInputPropertiesReturnsArrayWithPropertiesIfTemplatePropertiesConfigIsNotEmpty(): void
    {
        $result = $this->templateProperty->collectInputProperties(
            (new Template())->setPropertiesConfig(['prop' => ['name' => 'foo', 'description' => 'bar']])
        );
        $this->assertCount(1, $result);
        $this->assertContainsOnlyInstancesOf(PropertyInterface::class, $result);
    }
}
