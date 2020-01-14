<?php

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