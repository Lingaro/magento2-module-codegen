<?php

namespace Orba\Magento2Codegen\Test\Unit\Service\PropertyFactory;

use Orba\Magento2Codegen\Model\ArrayProperty;
use Orba\Magento2Codegen\Service\PropertyBuilder;
use Orba\Magento2Codegen\Service\PropertyFactory;
use Orba\Magento2Codegen\Service\PropertyFactory\ArrayFactory;
use Orba\Magento2Codegen\Test\Unit\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use RuntimeException;

class ArrayFactoryTest extends TestCase
{
    /**
     * @var ArrayFactory
     */
    private $arrayFactory;

    /**
     * @var MockObject|PropertyFactory
     */
    private $propertyFactoryMock;

    public function setUp(): void
    {
        $this->propertyFactoryMock = $this->getMockBuilder(PropertyFactory::class)
            ->disableOriginalConstructor()->getMock();
        $this->arrayFactory = new ArrayFactory(new PropertyBuilder());
    }

    public function testCreateThrowsExceptionIfPropertyFactoryIsUnset(): void
    {
        $this->expectException(RuntimeException::class);
        $this->arrayFactory->create('name', []);
    }

    public function testCreateReturnsArrayProperty(): void
    {
        $this->arrayFactory->setPropertyFactory($this->propertyFactoryMock);
        $result = $this->arrayFactory->create('name', ['children' => ['foo' => []]]);
        $this->assertInstanceOf(ArrayProperty::class, $result);
    }
}
