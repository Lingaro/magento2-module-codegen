<?php

namespace Orba\Magento2Codegen\Model\Property\Traits;

use Orba\Magento2Codegen\Model\Property\Interfaces\ValueInterface;

/**
 * Trait ValueTrait
 * @package Orba\Magento2Codegen\Model\Property\Traits
 */
trait ValueTrait
{
    /**
     * @var string|null
     */
    protected $value;

    /**
     * @return string|null
     */
    public function getValue(): ?string
    {
        return $this->value;
    }

    /**
     * @param string $value
     * @return $this|ValueInterface
     */
    public function setValue(string $value): ValueInterface
    {
        $this->value = $value;
        return $this;
    }
}