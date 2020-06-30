<?php

namespace Orba\Magento2Codegen\Model\Property\Traits;

use Orba\Magento2Codegen\Model\Property\Interfaces\ChildrenInterface;
use Orba\Magento2Codegen\Model\PropertyInterface;

/**
 * Trait ChildrenTrait
 * @package Orba\Magento2Codegen\Model\Property\Traits
 */
trait ChildrenTrait
{

    /**
     * @var PropertyInterface[]
     */
    protected $children = [];

    /**
     * @return PropertyInterface[]
     */
    public function getChildren(): array
    {
        return $this->children;
    }

    /**
     * @param array $children
     * @return $this|ChildrenInterface
     */
    public function setChildren(array $children): ChildrenInterface
    {
        $this->children = $children;
        return $this;
    }
}
