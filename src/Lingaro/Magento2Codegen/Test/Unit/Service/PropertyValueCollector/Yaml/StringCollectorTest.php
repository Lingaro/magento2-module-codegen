<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Orba\Magento2Codegen\Test\Unit\Service\PropertyValueCollector\Yaml;

use InvalidArgumentException;
use Orba\Magento2Codegen\Exception\ValueNotSetException;
use Orba\Magento2Codegen\Model\StringProperty;
use Orba\Magento2Codegen\Model\ConstProperty;
use Orba\Magento2Codegen\Service\PropertyValueCollector\Yaml\StringCollector;
use Orba\Magento2Codegen\Service\PropertyValueCollector\Yaml\DataProvider;
use Orba\Magento2Codegen\Service\PropertyValueCollector\Yaml\DataProviderRegistry;
use Orba\Magento2Codegen\Service\StringValidator;
use Orba\Magento2Codegen\Test\Unit\TestCase;
use Orba\Magento2Codegen\Util\PropertyBag;
use PHPUnit\Framework\MockObject\MockObject;

class StringCollectorTest extends TestCase
{
    private StringCollector $stringCollector;
    private DataProviderRegistry $dataProviderRegistry;

    /**
     * @var StringValidator|MockObject
     */
    private $stringValidatorMock;

    public function setUp(): void
    {
        $this->dataProviderRegistry = new DataProviderRegistry();
        $this->stringValidatorMock = $this->getMockBuilder(StringValidator::class)
            ->disableOriginalConstructor()->getMock();
        $this->stringCollector = new StringCollector($this->dataProviderRegistry, $this->stringValidatorMock);
    }

    public function testCollectValueThrowsExceptionIfPropertyIsNotStringProperty(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->stringCollector->collectValue(new ConstProperty(), new PropertyBag());
    }

    /**
     * @dataProvider emptyValues()
     */
    public function testCollectValueThrowsExceptionIfPropertyIsRequiredButValueIsEmpty($value): void
    {
        $this->expectException(ValueNotSetException::class);
        $property = new StringProperty();
        $property->setName('property');
        $property->setRequired(true);
        $dataProvider = new DataProvider(['property' => $value]);
        $this->dataProviderRegistry->set($dataProvider);
        $this->stringCollector->collectValue($property, new PropertyBag());
    }

    public function testCollectValueReturnsDataFromDataProvider(): void
    {
        $property = new StringProperty();
        $property->setName('property');
        $dataProvider = new DataProvider(['property' => 'string']);
        $this->dataProviderRegistry->set($dataProvider);
        $result = $this->stringCollector->collectValue($property, new PropertyBag());
        $this->assertSame('string', $result);
    }

    public function emptyValues(): array
    {
        return [
            [null],
            ['']
        ];
    }
}
