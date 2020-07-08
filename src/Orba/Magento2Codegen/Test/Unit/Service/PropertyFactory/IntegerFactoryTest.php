<?php

namespace Orba\Magento2Codegen\Test\Unit\Service\PropertyFactory;

use Orba\Magento2Codegen\Model\IntegerProperty;
use Orba\Magento2Codegen\Service\PropertyBuilder;
use Orba\Magento2Codegen\Service\PropertyFactory\IntegerFactory;
use Orba\Magento2Codegen\Test\Unit\TestCase;

class IntegerFactoryTest extends TestCase
{
    /**
     * @var IntegerFactory
     */
    private $integerFactory;

    public function setUp(): void
    {
        $this->integerFactory = new IntegerFactory(new PropertyBuilder());
    }

    public function testCreateReturnsIntegerProperty(): void
    {
        $result = $this->integerFactory->create('name', []);
        $this->assertInstanceOf(IntegerProperty::class, $result);
    }
}
