<?php

namespace Orba\Magento2Codegen\Test\Unit\Service\PropertyFactory;

use Orba\Magento2Codegen\Model\ChoiceProperty;
use Orba\Magento2Codegen\Service\PropertyBuilder;
use Orba\Magento2Codegen\Service\PropertyFactory\ChoiceFactory;
use Orba\Magento2Codegen\Test\Unit\TestCase;

class ChoiceFactoryTest extends TestCase
{
    /**
     * @var ChoiceFactory
     */
    private $choiceFactory;

    public function setUp(): void
    {
        $this->choiceFactory = new ChoiceFactory(new PropertyBuilder());
    }

    public function testCreateReturnsChoiceProperty(): void
    {
        $result = $this->choiceFactory->create('name', ['options' => ['foo', 'bar']]);
        $this->assertInstanceOf(ChoiceProperty::class, $result);
    }
}
