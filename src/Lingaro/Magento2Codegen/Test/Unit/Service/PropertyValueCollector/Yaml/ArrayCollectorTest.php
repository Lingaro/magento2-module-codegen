<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Lingaro\Magento2Codegen\Test\Unit\Service\PropertyValueCollector\Yaml;

use InvalidArgumentException;
use Lingaro\Magento2Codegen\Exception\ValueInvalidException;
use Lingaro\Magento2Codegen\Exception\ValueNotSetException;
use Lingaro\Magento2Codegen\Model\ArrayProperty;
use Lingaro\Magento2Codegen\Model\BooleanProperty;
use Lingaro\Magento2Codegen\Model\ConstProperty;
use Lingaro\Magento2Codegen\Service\PropertyDependencyChecker;
use Lingaro\Magento2Codegen\Service\PropertyValueCollector\CollectorFactory;
use Lingaro\Magento2Codegen\Service\PropertyValueCollector\Yaml\ArrayCollector;
use Lingaro\Magento2Codegen\Service\PropertyValueCollector\Yaml\BooleanCollector;
use Lingaro\Magento2Codegen\Service\PropertyValueCollector\Yaml\DataProvider;
use Lingaro\Magento2Codegen\Service\PropertyValueCollector\Yaml\DataProviderRegistry;
use Lingaro\Magento2Codegen\Test\Unit\TestCase;
use Lingaro\Magento2Codegen\Util\PropertyBag;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class ArrayCollectorTest extends TestCase
{
    private ArrayCollector $arrayCollector;
    private DataProviderRegistry $dataProviderRegistry;

    public function setUp(): void
    {
        $this->dataProviderRegistry = new DataProviderRegistry();
        $propertyDependencyChecker = new PropertyDependencyChecker();
        $this->arrayCollector = new ArrayCollector($this->dataProviderRegistry, $propertyDependencyChecker);
    }

    public function testCollectValueThrowsExceptionIfPropertyIsNotArrayProperty(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->arrayCollector->collectValue(new ConstProperty(), new PropertyBag());
    }

    /**
     * @dataProvider emptyValues()
     */
    public function testCollectValueThrowsExceptionIfPropertyIsRequiredButValueIsEmpty($value): void
    {
        $this->expectException(ValueNotSetException::class);
        $property = new ArrayProperty();
        $property->setName('property');
        $property->setRequired(true);
        $dataProvider = new DataProvider(['property' => $value]);
        $this->dataProviderRegistry->set($dataProvider);
        $this->setCollectorFactory();
        $this->arrayCollector->collectValue($property, new PropertyBag());
    }

    public function testCollectValueThrowsExceptionIfValueIsNotArray(): void
    {
        $this->expectException(ValueInvalidException::class);
        $property = new ArrayProperty();
        $property->setName('property');
        $property->setRequired(true);
        $dataProvider = new DataProvider(['property' => 'string']);
        $this->dataProviderRegistry->set($dataProvider);
        $this->setCollectorFactory();
        $this->arrayCollector->collectValue($property, new PropertyBag());
    }

    public function testCollectValueReturnsDataFromDataProvider(): void
    {
        $childProperty = new BooleanProperty();
        $childProperty->setName('flag');
        $property = new ArrayProperty();
        $property->setName('property');
        $property->setChildren([$childProperty]);
        $dataProvider = new DataProvider(['property' => [['flag' => true], ['flag' => false]]]);
        $this->dataProviderRegistry->set($dataProvider);
        $this->setCollectorFactory();
        $result = $this->arrayCollector->collectValue($property, new PropertyBag());
        $this->assertSame([['flag' => true], ['flag' => false]], $result);
    }

    public function emptyValues(): array
    {
        return [
            [null],
            [[]]
        ];
    }

    private function setCollectorFactory(): void
    {
        $collectorFactory = new CollectorFactory([
            'yaml' => [
                'Lingaro\Magento2Codegen\Model\BooleanProperty' => new BooleanCollector($this->dataProviderRegistry)
            ]
        ]);
        $this->arrayCollector->setCollectorFactory($collectorFactory);
    }
}
