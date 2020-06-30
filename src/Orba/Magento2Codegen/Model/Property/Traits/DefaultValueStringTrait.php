<?php

namespace Orba\Magento2Codegen\Model\Property\Traits;

use Orba\Magento2Codegen\Model\Property\Interfaces\DefaultValueStringInterface;

/**
 * Trait DefaultValueStringTrait
 * @package Orba\Magento2Codegen\Model\Property\Traits
 */
trait DefaultValueStringTrait
{
    /**
     * @var string|null
     */
    protected $defaultValue;

    /**
     * @param string $value
     * @return $this|DefaultValueStringInterface
     */
    public function setDefaultValue(string $value): DefaultValueStringInterface
    {
        $this->defaultValue = $value;
        return $this;
    }

    public function getDefaultValue(): ?string
    {
        return $this->defaultValue;
    }
}
