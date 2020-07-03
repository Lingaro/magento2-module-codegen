<?php

namespace Orba\Magento2Codegen\Test\Unit\Service\PropertyFactory;

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

    public function testCreateReturnsConstProperty(): void
    {
        $result = $this->constFactory->create('name', ['value' => 'foo']);
        $this->assertInstanceOf(ConstProperty::class, $result);
    }
}
