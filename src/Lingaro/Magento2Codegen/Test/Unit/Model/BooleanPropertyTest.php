<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Lingaro\Magento2Codegen\Test\Unit\Model;

use Lingaro\Magento2Codegen\Model\BooleanProperty;
use Lingaro\Magento2Codegen\Test\Unit\TestCase;

class BooleanPropertyTest extends TestCase
{
    private BooleanProperty $property;

    public function setUp(): void
    {
        $this->property = new BooleanProperty();
    }

    /**
     * @dataProvider defaultValues
     */
    public function testGetDefaultValueReturnsBool($defaultValue, bool $expectedValue): void
    {
        $this->property->setDefaultValue($defaultValue);
        $result = $this->property->getDefaultValue();
        $this->assertSame($expectedValue, $result);
    }

    public function defaultValues(): array
    {
        return [
            [null, false],
            [true, true],
            [false, false],
            ['string', true],
            [1, true],
            [0, false]
        ];
    }
}
