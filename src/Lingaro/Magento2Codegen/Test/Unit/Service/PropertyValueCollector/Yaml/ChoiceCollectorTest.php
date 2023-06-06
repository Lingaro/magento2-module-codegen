<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Lingaro\Magento2Codegen\Test\Unit\Service\PropertyValueCollector\Yaml;

use InvalidArgumentException;
use Lingaro\Magento2Codegen\Exception\ValueNotAllowedException;
use Lingaro\Magento2Codegen\Exception\ValueNotSetException;
use Lingaro\Magento2Codegen\Model\ChoiceProperty;
use Lingaro\Magento2Codegen\Model\ConstProperty;
use Lingaro\Magento2Codegen\Service\PropertyValueCollector\Yaml\ChoiceCollector;
use Lingaro\Magento2Codegen\Service\PropertyValueCollector\Yaml\DataProvider;
use Lingaro\Magento2Codegen\Service\PropertyValueCollector\Yaml\DataProviderRegistry;
use Lingaro\Magento2Codegen\Test\Unit\TestCase;
use Lingaro\Magento2Codegen\Util\PropertyBag;

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
