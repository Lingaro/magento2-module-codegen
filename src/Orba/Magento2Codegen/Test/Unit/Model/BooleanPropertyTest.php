<?php

/**
 * @copyright Copyright © 2021 Orba. All rights reserved.
 * @author    info@orba.co
 */

declare(strict_types=1);

namespace Orba\Magento2Codegen\Test\Unit\Model;

use Orba\Magento2Codegen\Model\BooleanProperty;
use Orba\Magento2Codegen\Test\Unit\TestCase;

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
