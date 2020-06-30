<?php

namespace Orba\Magento2Codegen\Model\Property\Interfaces;

/**
 * Interface RequiredInterface
 * @package Orba\Magento2Codegen\Model\Property\Interfaces
 */
interface RequiredInterface
{
    public function getRequired(): bool;

    public function setRequired(string $value): RequiredInterface;
}
