<?php

namespace Orba\Magento2Codegen\Model;

/**
 * Class ConstProperty
 * @package Orba\Magento2Codegen\Model
 */
class ConstProperty extends AbstractProperty
{
    const TYPE = 'const';

    /**
     * @var string|null
     */
    protected $value;

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): ConstProperty
    {
        $this->value = $value;
        return $this;
    }
}
