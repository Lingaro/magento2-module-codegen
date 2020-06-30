<?php

namespace Orba\Magento2Codegen\Test\Unit\Service\PropertyFactory;

use InvalidArgumentException;
use Orba\Magento2Codegen\Model\ConstProperty;
use Orba\Magento2Codegen\Service\PropertyBuilder;
use Orba\Magento2Codegen\Service\PropertyFactory\ConstFactory;
use Orba\Magento2Codegen\Test\Unit\TestCase;

class ConstFactoryTest extends TestCase
{
    /**
     * @var ConstFactory
     */
    private $constFactory;

    public function setUp(): void
    {
        $this->constFactory = new ConstFactory(new PropertyBuilder());
    }

    public function testCreateThrowsExceptionIfNameIsEmpty(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->constFactory->create('', []);
    }

    public function testCreateThrowsExceptionIfValueNotDefined(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->constFactory->create('name', ['no' => 'value']);
    }

    public function testCreateReturnsConstPropertyIfConfigIsValid(): void
    {
        $result = $this->constFactory->create('name', ['value' => 'foo']);
        $this->assertInstanceOf(ConstProperty::class, $result);
    }
}