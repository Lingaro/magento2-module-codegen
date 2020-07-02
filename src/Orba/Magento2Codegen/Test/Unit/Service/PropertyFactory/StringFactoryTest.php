<?php

namespace Orba\Magento2Codegen\Test\Unit\Service\PropertyFactory;

use Orba\Magento2Codegen\Model\StringProperty;
use Orba\Magento2Codegen\Service\PropertyBuilder;
use Orba\Magento2Codegen\Service\PropertyFactory\StringFactory;
use Orba\Magento2Codegen\Test\Unit\TestCase;

class StringFactoryTest extends TestCase
{
    /**
     * @var StringFactory
     */
    private $stringFactory;

    public function setUp(): void
    {
        $this->stringFactory = new StringFactory(new PropertyBuilder());
    }

    public function testCreateReturnsStringProperty(): void
    {
        $result = $this->stringFactory->create('name', []);
        $this->assertInstanceOf(StringProperty::class, $result);
    }
}
