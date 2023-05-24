<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Lingaro\Magento2Codegen\Test\Unit\Service\PropertyFactory;

use Lingaro\Magento2Codegen\Model\ConstProperty;
use Lingaro\Magento2Codegen\Service\PropertyBuilder;
use Lingaro\Magento2Codegen\Service\PropertyFactory\ConstFactory;
use Lingaro\Magento2Codegen\Service\StringValidator;
use Lingaro\Magento2Codegen\Test\Unit\TestCase;

class ConstFactoryTest extends TestCase
{
    private ConstFactory $constFactory;

    public function setUp(): void
    {
        $this->constFactory = new ConstFactory(new PropertyBuilder());
    }

    public function testCreateReturnsConstProperty(): void
    {
        $result = $this->constFactory->create('name', ['value' => 'foo']);
        $this->assertInstanceOf(ConstProperty::class, $result);
    }
}
