<?php

namespace Orba\Magento2Codegen\Model\Property\Traits;

use Orba\Magento2Codegen\Model\Property\Interfaces\DefaultValueBooleanInterface;

/**
 * Trait DefaultBooleanValueTrait
 * @package Orba\Magento2Codegen\Model\Property\Traits
 */
trait DefaultValueBooleanTrait
{
    /**
     * @var bool
     */
    private $defaultValue;

    /**
     * @return bool
     */
    public function getDefaultValue(): bool
    {
        return (bool)$this->defaultValue;
    }

    /**
     * @param $value
     * @return $this|DefaultValueBooleanInterface
     */
    public function setDefaultValue($value): DefaultValueBooleanInterface
    {
        if (!is_bool($value)) {
            throw new \InvalidArgumentException('Default value must be boolean.');
        }
        $this->defaultValue = $value;
        return $this;
    }
}
