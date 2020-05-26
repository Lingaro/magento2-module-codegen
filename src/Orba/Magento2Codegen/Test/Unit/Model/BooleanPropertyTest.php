<?php

namespace Orba\Magento2Codegen\Test\Unit\Model;

use Orba\Magento2Codegen\Model\BooleanProperty;
use Orba\Magento2Codegen\Test\Unit\TestCase;

class BooleanPropertyTest extends TestCase
{
    /**
     * @var BooleanProperty
     */
    private $booleanProperty;

    public function setUp(): void
    {
        $this->booleanProperty = new BooleanProperty();
    }

    public function testSetDefaultValueThrowsExceptionForNonBooleanValue(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->booleanProperty->setDefaultValue('not bool');
    }

    public function testSetDefaultValueSetsValueForBooleanValue(): void
    {
        $this->booleanProperty->setDefaultValue(true);
        $this->assertSame(true, $this->booleanProperty->getDefaultValue());
    }
}
