<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Orba\Magento2Codegen\Test\Unit\Service\PropertyValueCollector\Yaml;

use InvalidArgumentException;
use Orba\Magento2Codegen\Model\ConstProperty;
use Orba\Magento2Codegen\Model\BooleanProperty;
use Orba\Magento2Codegen\Service\PropertyValueCollector\Yaml\BooleanCollector;
use Orba\Magento2Codegen\Service\PropertyValueCollector\Yaml\DataProvider;
use Orba\Magento2Codegen\Service\PropertyValueCollector\Yaml\DataProviderRegistry;
use Orba\Magento2Codegen\Test\Unit\TestCase;
use Orba\Magento2Codegen\Util\PropertyBag;

class BooleanCollectorTest extends TestCase
{
    private BooleanCollector $booleanCollector;
    private DataProviderRegistry $dataProviderRegistry;

    public function setUp(): void
    {
        $this->dataProviderRegistry = new DataProviderRegistry();
        $this->booleanCollector = new BooleanCollector($this->dataProviderRegistry);
    }

    public function testCollectValueThrowsExceptionIfPropertyIsNotBooleanProperty(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->booleanCollector->collectValue(new ConstProperty(), new PropertyBag());
    }

    public function testCollectValueThrowsExceptionIfValueIsNotBoolean(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $property = new BooleanProperty();
        $property->setName('property');
        $dataProvider = new DataProvider(['property' => 'string']);
        $this->dataProviderRegistry->set($dataProvider);
        $this->booleanCollector->collectValue($property, new PropertyBag());
    }

    public function testCollectValueReturnsValueFromDataProvider(): void
    {
        $property = new BooleanProperty();
        $property->setName('property');
        $dataProvider = new DataProvider(['property' => true]);
        $this->dataProviderRegistry->set($dataProvider);
        $result = $this->booleanCollector->collectValue($property, new PropertyBag());
        $this->assertTrue($result);
    }
}
