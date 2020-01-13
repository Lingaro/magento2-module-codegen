<?php

namespace Orba\Magento2Codegen\Test\Unit\Service\CommandUtil;

use Orba\Magento2Codegen\Model\PropertyInterface;
use Orba\Magento2Codegen\Service\CommandUtil\TemplateProperty;
use Orba\Magento2Codegen\Service\Config;
use Orba\Magento2Codegen\Service\PropertyFactory;
use Orba\Magento2Codegen\Service\PropertyValueCollector\CollectorFactory;
use Orba\Magento2Codegen\Service\TemplateFile;
use Orba\Magento2Codegen\Test\Unit\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class TemplatePropertyTest extends TestCase
{
    /**
     * @var TemplateProperty
     */
    private $templateProperty;

    /**
     * @var MockObject|Config
     */
    private $configMock;

    /**
     * @var MockObject|TemplateFile
     */
    private $templateFileMock;

    /**
     * @var MockObject|CollectorFactory
     */
    private $propertyValueCollectorFactoryMock;

    /**
     * @var MockObject|PropertyFactory
     */
    private $propertyFactoryMock;

    public function setUp(): void
    {
        $this->configMock = $this->getMockBuilder(Config::class)->disableOriginalConstructor()->getMock();
        $this->templateFileMock = $this->getMockBuilder(TemplateFile::class)->disableOriginalConstructor()
            ->getMock();
        $this->propertyValueCollectorFactoryMock = $this->getMockBuilder(CollectorFactory::class)
            ->disableOriginalConstructor()->getMock();
        $this->propertyFactoryMock = $this->getMockBuilder(PropertyFactory::class)
            ->disableOriginalConstructor()->getMock();
        $this->templateProperty = new TemplateProperty(
            $this->configMock,
            $this->templateFileMock,
            $this->propertyValueCollectorFactoryMock,
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
        $result = $this->templateProperty->collectInputProperties('template');
        $this->assertSame([], $result);
    }

    public function testCollectInputPropertiesReturnsArrayWithPropertiesIfTemplatePropertiesConfigIsNotEmpty(): void
    {
        $this->templateFileMock->expects($this->once())->method('getPropertiesConfig')
            ->willReturn([['name' => 'foo', 'description' => 'bar']]);
        $result = $this->templateProperty->collectInputProperties('template');
        $this->assertCount(1, $result);
        $this->assertContainsOnlyInstancesOf(PropertyInterface::class, $result);
    }
}
