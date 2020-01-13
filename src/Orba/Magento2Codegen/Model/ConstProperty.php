<?php

namespace Orba\Magento2Codegen\Model;

class ConstProperty extends AbstractProperty
{
    const TYPE = 'const';

    /**
     * @var string|null
     */
    protected $value;

    /**
     * @param string $value
     * @return $this
     */
    public function setValue(string $value): PropertyInterface
    {
        $this->value = $value;
        return $this;
    }

    public function getValue():? string
    {
        return $this->value;
    }
}