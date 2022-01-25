<?php

/**
 * @copyright Copyright © 2021 Orba. All rights reserved.
 * @author    info@orba.co
 */

declare(strict_types=1);

namespace Orba\Magento2Codegen\Test\Unit\Service;

use InvalidArgumentException;
use Orba\Magento2Codegen\Model\ArrayProperty;
use Orba\Magento2Codegen\Model\BooleanProperty;
use Orba\Magento2Codegen\Model\ChoiceProperty;
use Orba\Magento2Codegen\Model\ConstProperty;
use Orba\Magento2Codegen\Model\PropertyInterface;
use Orba\Magento2Codegen\Model\StringProperty;
use Orba\Magento2Codegen\Service\PropertyBuilder;
use Orba\Magento2Codegen\Service\PropertyFactory;
use Orba\Magento2Codegen\Service\StringValidator;
use Orba\Magento2Codegen\Test\Unit\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 * @SuppressWarnings(PHPMD.TooManyMethods)
 */
class PropertyBuilderTest extends TestCase
{
    private PropertyBuilder $propertyBuilder;

    public function setUp(): void
    {
        $this->propertyBuilder = new PropertyBuilder();
    }

    public function testAddNameThrowsExceptionIfNameIsEmpty(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->propertyBuilder->addName(new ConstProperty(), '');
    }

    /**
     * @dataProvider invalidNames
     */
    public function testAddNameThrowsExceptionIfNameHasInvalidChars(string $invalidName): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->propertyBuilder->addName(new ConstProperty(), $invalidName);
    }

    /**
     * @dataProvider validNames
     */
    public function testAddNameSetsName(string $validName): void
    {
        $property = new ConstProperty();
        $result = $this->propertyBuilder->addName($property, $validName);
        $this->assertSame($validName, $property->getName());
        $this->assertSame($result, $this->propertyBuilder);
    }

    public function testAddDescriptionDoesNotSetDescriptionIfConfigNotFound(): void
    {
        $property = new ConstProperty();
        $result = $this->propertyBuilder->addDescription($property, []);
        $this->assertNull($property->getDescription());
        $this->assertSame($result, $this->propertyBuilder);
    }

    public function testAddDescriptionThrowsExceptionIfDescriptionIsNotString(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $property = new ConstProperty();
        $this->propertyBuilder->addDescription($property, ['description' => true]);
    }

    public function testAddDescriptionSetsDescription(): void
    {
        $property = new ConstProperty();
        $result = $this->propertyBuilder->addDescription($property, ['description' => 'foo']);
        $this->assertSame('foo', $property->getDescription());
        $this->assertSame($result, $this->propertyBuilder);
    }

    public function testAddDependDoesNotSetDependIfDependNotFound(): void
    {
        $property = new StringProperty();
        $result = $this->propertyBuilder->addDepend($property, []);
        $this->assertEmpty($property->getDepend());
        $this->assertSame($result, $this->propertyBuilder);
    }

    public function testAddDependThrowsExceptionIfDependIsNotArray(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $property = new StringProperty();
        $this->propertyBuilder->addDepend($property, ['depend' => 'foo']);
    }

    public function testAddDependThrowsExceptionIfDependArrayHasNonStringKey(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $property = new StringProperty();
        $this->propertyBuilder->addDepend($property, ['depend' => ['foo']]);
    }

    public function testAddDependThrowsExceptionIfDependArrayIsMultidimensional(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $property = new StringProperty();
        $this->propertyBuilder->addDepend($property, ['depend' => ['foo' => ['bar']]]);
    }

    public function testAddDependSetsDepend(): void
    {
        $property = new StringProperty();
        $result = $this->propertyBuilder->addDepend($property, ['depend' => ['foo' => 'bar']]);
        $this->assertSame(['foo' => 'bar'], $property->getDepend());
        $this->assertSame($result, $this->propertyBuilder);
    }

    public function testAddRequiredDoesNotSetRequiredIfRequiredNotFound(): void
    {
        $property = new StringProperty();
        $result = $this->propertyBuilder->addRequired($property, []);
        $this->assertFalse($property->getRequired());
        $this->assertSame($result, $this->propertyBuilder);
    }

    public function testAddRequiredThrowsExceptionIfRequiredIsNotBool(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $property = new StringProperty();
        $this->propertyBuilder->addRequired($property, ['required' => 'foo']);
    }

    public function testAddRequiredSetsRequired(): void
    {
        $property = new StringProperty();
        $result = $this->propertyBuilder->addRequired($property, ['required' => true]);
        $this->assertTrue($property->getRequired());
        $this->assertSame($result, $this->propertyBuilder);
    }

    public function testAddDefaultValueDoesNotSetDefaultValueIfConfigNotFound(): void
    {
        $property = new StringProperty();
        $result = $this->propertyBuilder->addDefaultValue($property, []);
        $this->assertNull($property->getDefaultValue());
        $this->assertSame($result, $this->propertyBuilder);
    }

    public function testAddDefaultValueThrowsExceptionForArrayProperty(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $property = new ArrayProperty();
        $this->propertyBuilder->addDefaultValue($property, ['default' => 'something']);
    }

    public function testAddDefaultValueThrowsExceptionForChoicePropertyIfOptionsNotSet(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $property = new ChoiceProperty();
        $this->propertyBuilder->addDefaultValue($property, ['default' => 'something']);
    }

    public function testAddDefaultValueThrowsExceptionForChoicePropertyIfValueNotFoundInOptions(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $property = new ChoiceProperty();
        $property->setOptions(['foo', 'bar']);
        $this->propertyBuilder->addDefaultValue($property, ['default' => 'something']);
    }

    public function testAddDefaultValueSetsDefaultValueForChoiceProperty(): void
    {
        $property = new ChoiceProperty();
        $property->setOptions(['foo', 'bar']);
        $result = $this->propertyBuilder->addDefaultValue($property, ['default' => 'bar']);
        $this->assertSame('bar', $property->getDefaultValue());
        $this->assertSame($result, $this->propertyBuilder);
    }

    public function testAddDefaultValueThrowsExceptionForStringPropertyIfValueIsNotString(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $property = new StringProperty();
        $this->propertyBuilder->addDefaultValue($property, ['default' => true]);
    }

    public function testAddDefaultValueSetsDefaultValueForStringProperty(): void
    {
        $property = new StringProperty();
        $result = $this->propertyBuilder->addDefaultValue($property, ['default' => 'foo']);
        $this->assertSame('foo', $property->getDefaultValue());
        $this->assertSame($result, $this->propertyBuilder);
    }

    public function testAddDefaultValueThrowsExceptionForBooleanPropertyIfValueIsNotBoolean(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $property = new BooleanProperty();
        $this->propertyBuilder->addDefaultValue($property, ['default' => 'string']);
    }

    public function testAddDefaultValueSetsDefaultValueForBooleanProperty(): void
    {
        $property = new BooleanProperty();
        $result = $this->propertyBuilder->addDefaultValue($property, ['default' => true]);
        $this->assertTrue($property->getDefaultValue());
        $this->assertSame($result, $this->propertyBuilder);
    }

    public function testAddChildrenThrowsExceptionIfChildrenNotFoundInConfig(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->propertyBuilder->addChildren(new ArrayProperty(), $this->getPropertyFactoryMock(), []);
    }

    public function testAddChildrenThrowsExceptionIfChildrenAreNotArray(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->propertyBuilder
            ->addChildren(new ArrayProperty(), $this->getPropertyFactoryMock(), ['children' => 'foo']);
    }

    public function testAddChildrenThrowsExceptionIfChildrenArrayIsEmpty(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->propertyBuilder
            ->addChildren(new ArrayProperty(), $this->getPropertyFactoryMock(), ['children' => []]);
    }

    public function testAddChildrenThrowsExceptionIfChildConfigIsNotAnArray(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->propertyBuilder
            ->addChildren(new ArrayProperty(), $this->getPropertyFactoryMock(), ['children' => ['string']]);
    }

    public function testAddChildrenSetsChildren(): void
    {
        $property = new ArrayProperty();
        $result = $this->propertyBuilder
            ->addChildren($property, $this->getPropertyFactoryMock(), ['children' => ['child' => []]]);
        $this->assertContainsOnlyInstancesOf(PropertyInterface::class, $property->getChildren());
        $this->assertSame($result, $this->propertyBuilder);
    }

    public function testAddValueThrowsExceptionIfValueNotFoundInConfig(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->propertyBuilder->addValue(new ConstProperty(), []);
    }

    public function testAddValueSetsValue(): void
    {
        $property = new ConstProperty();
        $result = $this->propertyBuilder->addValue($property, ['value' => 'foo']);
        $this->assertSame('foo', $property->getValue());
        $this->assertSame($result, $this->propertyBuilder);
    }

    public function testAddOptionsThrowsExceptionIfOptionsNotFoundInConfig(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->propertyBuilder->addOptions(new ChoiceProperty(), []);
    }

    public function testAddOptionsThrowsExceptionIfOptionsAreNotArray(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->propertyBuilder->addOptions(new ChoiceProperty(), ['options' => 'foo']);
    }

    public function testAddOptionsThrowsExceptionIfOptionsArrayIsEmpty(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->propertyBuilder->addOptions(new ChoiceProperty(), ['options' => []]);
    }

    public function testAddOptionsThrowsExceptionIfOptionsArrayHasValueDifferentThanString(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->propertyBuilder->addOptions(new ChoiceProperty(), ['options' => [true]]);
    }

    public function testAddOptionsSetsOptions(): void
    {
        $property = new ChoiceProperty();
        $result = $this->propertyBuilder->addOptions($property, ['options' => ['foo', 'bar']]);
        $this->assertSame(['foo', 'bar'], $property->getOptions());
        $this->assertSame($result, $this->propertyBuilder);
    }

    public function invalidNames(): array
    {
        return [
            ['has.dot'],
            ['has space'],
            ["has\nnewline"],
            ['9startswithnumber']
        ];
    }

    public function validNames(): array
    {
        return [
            ['hassmallletters'],
            ['HASLARGELETTERS'],
            ['hasnumb3r'],
            ['has_underscore'],
            ['_startsWithUnderscore']
        ];
    }

    /**
     * @return MockObject|PropertyFactory
     */
    private function getPropertyFactoryMock()
    {
        return $this->getMockBuilder(PropertyFactory::class)->disableOriginalConstructor()->getMock();
    }
}
