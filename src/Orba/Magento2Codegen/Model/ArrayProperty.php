<?php

/**
 * @copyright Copyright © 2021 Orba. All rights reserved.
 * @author    info@orba.co
 */

declare(strict_types=1);

namespace Orba\Magento2Codegen\Model;

class ArrayProperty extends AbstractInputProperty
{
    /**
     * @var PropertyInterface[]
     */
    protected array $children = [];

    /**
     * @return PropertyInterface[]
     */
    public function getChildren(): array
    {
        return $this->children;
    }

    /**
     * @param PropertyInterface[] $children
     */
    public function setChildren(array $children): self
    {
        $this->children = $children;
        return $this;
    }
}
