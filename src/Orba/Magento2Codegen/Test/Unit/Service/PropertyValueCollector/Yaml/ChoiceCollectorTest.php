<?php

/**
 * @copyright Copyright © 2022 Orba. All rights reserved.
 * @author    info@orba.co
 */

declare(strict_types=1);

namespace Orba\Magento2Codegen\Test\Unit\Service\PropertyValueCollector\Yaml;

use InvalidArgumentException;
use Orba\Magento2Codegen\Exception\ValueNotAllowedException;
use Orba\Magento2Codegen\Exception\ValueNotSetException;
use Orba\Magento2Codegen\Model\ChoiceProperty;
use Orba\Magento2Codegen\Model\ConstProperty;
use Orba\Magento2Codegen\Service\PropertyValueCollector\Yaml\ChoiceCollector;
use Orba\Magento2Codegen\Service\PropertyValueCollector\Yaml\DataProvider;
use Orba\Magento2Codegen\Service\PropertyValueCollector\Yaml\DataProviderRegistry;
use Orba\Magento2Codegen\Test\Unit\TestCase;
use Orba\Magento2Codegen\Util\PropertyBag;

class ChoiceCollectorTest extends TestCase
{
    private ChoiceCollector $choiceCollector;
    private DataProviderRegistry $dataProviderRegistry;

    public function setUp(): void
    {
        $this->dataProviderRegistry = new DataProviderRegistry();
        $this->choiceCollector = new ChoiceCollector($this->dataProviderRegistry);
    }

    public function testCollectValueThrowsExceptionIfPropertyIsNotChoiceProperty(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->choiceCollector->collectValue(new ConstProperty(), new PropertyBag());
    }

    public function testCollectValueThrowsExceptionIfPropertyIsRequiredButValueNotSet(): void
    {
        $this->expectException(ValueNotSetException::class);
        $property = new ChoiceProperty();
        $property->setName('property');
        $property->setRequired(true);
        $dataProvider = new DataProvider(['property' => null]);
        $this->dataProviderRegistry->set($dataProvider);
        $this->choiceCollector->collectValue($property, new PropertyBag());
    }

    public function testCollectValueThrowsExceptionIfValueNotAllowed(): void
    {
        $this->expectException(ValueNotAllowedException::class);
        $property = new ChoiceProperty();
        $property->setName('property');
        $property->setOptions(['foo', 'bar']);
        $dataProvider = new DataProvider(['property' => 'not']);
        $this->dataProviderRegistry->set($dataProvider);
        $this->choiceCollector->collectValue($property, new PropertyBag());
    }

    public function testCollectValueReturnsEmptyStringIfPropertyNotRequiredAndValueEmpty(): void
    {
        $property = new ChoiceProperty();
        $property->setName('property');
        $property->setRequired(false);
        $dataProvider = new DataProvider(['property' => null]);
        $this->dataProviderRegistry->set($dataProvider);
        $result = $this->choiceCollector->collectValue($property, new PropertyBag());
        $this->assertSame('', $result);
    }

    public function testCollectValueReturnsDataFromDataProvider(): void
    {
        $property = new ChoiceProperty();
        $property->setName('property');
        $property->setOptions(['foo', 'bar']);
        $dataProvider = new DataProvider(['property' => 'foo']);
        $this->dataProviderRegistry->set($dataProvider);
        $result = $this->choiceCollector->collectValue($property, new PropertyBag());
        $this->assertSame('foo', $result);
    }
}
