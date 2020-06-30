<?php

namespace Orba\Magento2Codegen\Model\Property\Interfaces;

use Orba\Magento2Codegen\Model\PropertyInterface;

/**
 * Interface ChildrenInterface
 * @package Orba\Magento2Codegen\Model\Property\Interfaces
 */
interface ChildrenInterface
{
    /**
     * @return PropertyInterface[]
     */
    public function getChildren(): array;

    /**
     * @param PropertyInterface[] $children
     * @return ChildrenInterface
     */
    public function setChildren(array $children): ChildrenInterface;
}