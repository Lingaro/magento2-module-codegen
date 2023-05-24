<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Orba\Magento2Codegen\Test\Unit\Service;

use InvalidArgumentException;
use Orba\Magento2Codegen\Model\PropertyInterface;
use Orba\Magento2Codegen\Service\PropertyFactory;
use Orba\Magento2Codegen\Test\Unit\TestCase;

class PropertyFactoryTest extends TestCase
{
    public function testCreateThrowsExceptionIfTypeNotDefined(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $propertyFactory = new PropertyFactory([]);
        $propertyFactory->create('name', ['no' => 'type']);
    }

    public function testCreateThrowsExceptionIfTypeNotMapped(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $propertyFactory = new PropertyFactory(['no' => 'type']);
        $propertyFactory->create('name', ['type' => 'foo']);
    }

    public function testCreateReturnsPropertyIfTypeMapped(): void
    {
        $fooFactoryMock = $this->getMockBuilder(PropertyFactory\FactoryInterface::class)
            ->getMockForAbstractClass();
        $propertyFactory = new PropertyFactory(['foo' => $fooFactoryMock]);
        $result = $propertyFactory->create('name', ['type' => 'foo']);
        $this->assertInstanceOf(PropertyInterface::class, $result);
    }
}
