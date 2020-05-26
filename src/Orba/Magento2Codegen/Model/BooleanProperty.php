<?php

namespace Orba\Magento2Codegen\Model;

class BooleanProperty extends AbstractProperty
{
    private $defaultValue;

    /**
     * @param mixed $value
     * @return $this
     */
    public function setDefaultValue($value): PropertyInterface
    {
        if (!is_bool($value)) {
            throw new \InvalidArgumentException('Default value must be boolean.');
        }
        $this->defaultValue = $value;
        return $this;
    }

    public function getDefaultValue(): bool
    {
        return $this->defaultValue;
    }
}
