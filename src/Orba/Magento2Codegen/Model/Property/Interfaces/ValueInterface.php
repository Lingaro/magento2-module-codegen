<?php

namespace Orba\Magento2Codegen\Model\Property\Interfaces;

/**
 * Interface ValueInterface
 * @package Orba\Magento2Codegen\Model\Property\Interfaces
 */
interface ValueInterface
{
    /**
     * @return string|null
     */
    public function getValue(): ?string;

    /**
     * @param string $value
     * @return $this|ValueInterface
     */
    public function setValue(string $value): ValueInterface;
}
