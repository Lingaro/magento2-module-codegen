<?php

namespace Orba\Magento2Codegen\Model;

/**
 * Class ArrayProperty
 * @package Orba\Magento2Codegen\Model
 */
class ArrayProperty extends AbstractInputProperty
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
     * @param PropertyInterface[] $children
     * @return $this
     */
    public function setChildren(array $children): self
    {
        $this->children = $children;
        return $this;
    }
}
