<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Lingaro\Magento2Codegen\Test\Unit\Service\PropertyFactory;

use Lingaro\Magento2Codegen\Model\StringProperty;
use Lingaro\Magento2Codegen\Service\PropertyBuilder;
use Lingaro\Magento2Codegen\Service\PropertyFactory\StringFactory;
use Lingaro\Magento2Codegen\Service\PropertyStringValidatorsAdder;
use Lingaro\Magento2Codegen\Test\Unit\TestCase;
use Lingaro\Magento2Codegen\Service\StringValidator;

class StringFactoryTest extends TestCase
{
    private StringFactory $stringFactory;

    public function setUp(): void
    {
        $validatorsAdderMock = $this->createPartialMock(PropertyStringValidatorsAdder::class, []);
        $this->stringFactory = new StringFactory(new PropertyBuilder(), $validatorsAdderMock);
    }

    public function testCreateReturnsStringProperty(): void
    {
        $result = $this->stringFactory->create('name', []);
        $this->assertInstanceOf(StringProperty::class, $result);
    }
}
