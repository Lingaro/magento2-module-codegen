<?php

namespace Orba\Magento2Codegen\Model\Property\Interfaces;

/**
 * Interface RequiredInterface
 * @package Orba\Magento2Codegen\Model\Property\Interfaces
 */
interface RequiredInterface
{
    /**
     * @return bool
     */
    public function getRequired(): bool;

    /**
     * @param string $value
     * @return RequiredInterface
     */
    public function setRequired(string $value): RequiredInterface;
}
