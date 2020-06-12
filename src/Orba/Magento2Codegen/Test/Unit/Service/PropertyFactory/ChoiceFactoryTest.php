<?php

namespace Orba\Magento2Codegen\Test\Unit\Service\PropertyFactory;

use InvalidArgumentException;
use Orba\Magento2Codegen\Model\ChoiceProperty;
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
        $this->choiceFactory = new ChoiceFactory();
    }

    public function testCreateThrowsExceptionIfNameIsEmpty(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->choiceFactory->create('', []);
    }

    public function testCreateThrowsExceptionIfOptionsAreNotSpecified(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->choiceFactory->create('field', []);
    }

    public function testCreateReturnsChoicePropertyIfConfigIsValid(): void
    {
        $result = $this->choiceFactory->create('name', ['options' => ['foo', 'bar']]);
        $this->assertInstanceOf(ChoiceProperty::class, $result);
    }
}
