<?php

namespace Orba\Magento2Codegen\Util;

use ArrayAccess;

class TemplatePropertyBag implements ArrayAccess
{
    /**
     * @var array
     */
    private $container = [];

    public function offsetSet($offset, $value): void
    {
        if (is_null($offset)) {
            $this->container[] = $value;
        } else {
            $this->container[$offset] = $value;
        }
    }

    public function offsetExists($offset): bool
    {
        return isset($this->container[$offset]);
    }

    public function offsetUnset($offset): void
    {
        unset($this->container[$offset]);
    }

    public function offsetGet($offset)
    {
        return isset($this->container[$offset]) ? $this->container[$offset] : null;
    }

    public function toArray(): array
    {
        return $this->container;
    }
}