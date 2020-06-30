<?php

namespace Orba\Magento2Codegen\Model\Property\Interfaces;

/**
 * Interface DependantInterface
 * @package Orba\Magento2Codegen\Model\Property\Interfaces
 */
interface DependantInterface
{
    /**
     * @return array|null
     */
    public function getDepend(): ?array;

    /**
     * @param array|null $value
     * @return DependantInterface
     */
    public function setDepend(?array $value): DependantInterface;
}
