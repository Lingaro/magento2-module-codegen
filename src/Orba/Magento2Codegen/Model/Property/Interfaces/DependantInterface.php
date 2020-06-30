<?php

namespace Orba\Magento2Codegen\Model\Property\Interfaces;

/**
 * Interface DependantInterface
 * @package Orba\Magento2Codegen\Model\Property\Interfaces
 */
interface DependantInterface
{
    public function getDepend(): ?array;

    public function setDepend(?array $value): DependantInterface;
}
