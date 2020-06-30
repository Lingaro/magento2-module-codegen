<?php

namespace Orba\Magento2Codegen\Test\Unit\Service\PropertyFactory;

use InvalidArgumentException;
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

    public function testCreateThrowsExceptionIfProprtyFactoryIsUnset(): void
    {
        $this->expectException(RuntimeException::class);
        $this->arrayFactory->create('name', ['children' => ['foo' => []]]);
    }

    public function testCreateThrowsExceptionIfNameIsEmpty(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->arrayFactory->setPropertyFactory($this->propertyFactoryMock);
        $this->arrayFactory->create('', ['children' => ['foo' => []]]);
    }

    public function testCreateThrowsExceptionIfChildrenAreUndefined(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->arrayFactory->setPropertyFactory($this->propertyFactoryMock);
        $this->arrayFactory->create('name', []);
    }

    public function testCreateThrowsExceptionIfChildrenAreEmpty(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->arrayFactory->setPropertyFactory($this->propertyFactoryMock);
        $this->arrayFactory->create('name', ['children' => []]);
    }

    public function testCreateReturnsArrayPropertyIfConfigIsValid(): void
    {
        $this->arrayFactory->setPropertyFactory($this->propertyFactoryMock);
        $result = $this->arrayFactory->create('name', ['children' => ['foo' => []]]);
        $this->assertInstanceOf(ArrayProperty::class, $result);
    }
}