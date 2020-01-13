<?php

namespace Orba\Magento2Codegen\Model;

class StringProperty extends AbstractProperty
{
    const TYPE = 'string';

    /**
     * @var string|null
     */
    protected $defaultValue;

    /**
     * @param string $value
     * @return $this
     */
    public function setDefaultValue(string $value): PropertyInterface
    {
        $this->defaultValue = $value;
        return $this;
    }

    public function getDefaultValue():? string
    {
        return $this->defaultValue;
    }
}