<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Orba\Magento2Codegen\Test\Unit\Util;

use Orba\Magento2Codegen\Test\Unit\TestCase;
use Orba\Magento2Codegen\Util\PropertyBag;

class TemplatePropertyBagTest extends TestCase
{
    public function testAddProperlyAddsProperties(): void
    {
        $propertyBag = new PropertyBag();
        $propertyBag->add(['foo' => 'bar']);
        $this->assertSame('bar', $propertyBag['foo']);
    }
}
