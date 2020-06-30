<?php

namespace Orba\Magento2Codegen\Model\Property\Interfaces;

/**
 * Interface DefaultValueStringInterface
 * @package Orba\Magento2Codegen\Model\Property\Interfaces
 */
interface DefaultValueStringInterface
{
    /**
     * @return string|null
     */
    public function getDefaultValue(): ?string;

    /**
     * @param string $value
     * @return $this|DefaultValueStringInterface
     */
    public function setDefaultValue(string $value): DefaultValueStringInterface;
}