<?php

namespace Orba\Magento2Codegen\Model;

class ArrayProperty extends AbstractProperty
{
    const TYPE = 'array';

    /**
     * @var PropertyInterface[]
     */
    protected $children = [];

    /**
     * @param PropertyInterface[] $children
     * @return $this
     */
    public function setChildren(array $children): PropertyInterface
    {
        $this->children = $children;
        return $this;
    }

    /**
     * @return PropertyInterface[]
     */
    public function getChildren(): array
    {
        return $this->children;
    }
}