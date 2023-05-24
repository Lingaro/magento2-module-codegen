<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Lingaro\Magento2Codegen\Util;

use ArrayAccess;

use function is_null;

class PropertyBag implements ArrayAccess
{
    private array $container = [];

    public function offsetSet($offset, $value): void
    {
        if (is_null($offset)) {
            $this->container[] = $value;
            return;
        }
        $this->container[$offset] = $value;
    }

    public function offsetExists($offset): bool
    {
        return isset($this->container[$offset]);
    }

    public function offsetUnset($offset): void
    {
        unset($this->container[$offset]);
    }

    #[\ReturnTypeWillChange]
    public function offsetGet($offset)
    {
        return $this->container[$offset] ?? null;
    }

    public function add(array $properties): void
    {
        foreach ($properties as $key => $value) {
            $this[$key] = $value;
        }
    }

    public function toArray(): array
    {
        return $this->container;
    }
}
