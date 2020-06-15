<?php

namespace Orba\Magento2Codegen\Test\Unit\Model;

use InvalidArgumentException;
use Orba\Magento2Codegen\Model\ChoiceProperty;
use Orba\Magento2Codegen\Test\Unit\TestCase;

class ChoicePropertyTest extends TestCase
{
    /**
     * @var ChoiceProperty
     */
    private $choiceProperty;

    public function setUp(): void
    {
        $this->choiceProperty = new ChoiceProperty();
    }

    public function testSetDefaultValueThrowsExceptionIfDefaultNotFoundInOptions(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->choiceProperty->setOptions(['foo', 'bar']);
        $this->choiceProperty->setDefaultValue('baz');
    }

    public function testSetDefaultValueSetsValueForValueExistingInOptions(): void
    {
        $this->choiceProperty->setOptions(['foo', 'bar']);
        $this->choiceProperty->setDefaultValue('bar');
        $this->assertSame('bar', $this->choiceProperty->getDefaultValue());
    }

    public function testSetOptionsThrowsExceptionIfOptionsArrayIsEmpty(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->choiceProperty->setOptions([]);
    }

    public function testSetOptionsThrowsExceptionIfOneOfTheOptionsIsNotAString(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->choiceProperty->setOptions(['one', 2, 'three']);
    }

    public function testSetOptionsSetsOptionsIfArrayIsValid(): void
    {
        $this->choiceProperty->setOptions(['one', 'two', 'three']);
        $this->assertSame(['one', 'two', 'three'], $this->choiceProperty->getOptions());
    }
}
