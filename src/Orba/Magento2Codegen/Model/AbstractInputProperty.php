<?php

namespace Orba\Magento2Codegen\Model;

abstract class AbstractInputProperty extends AbstractProperty implements InputPropertyInterface
{
    /**
     * @var mixed
     */
    protected $defaultValue;

    /**
     * @var bool
     */
    protected $required = false;

    /**
     * @var array
     */
    protected $depend = [];

    public function getDefaultValue()
    {
        return $this->defaultValue;
    }

    public function setDefaultValue($value): InputPropertyInterface
    {
        $this->defaultValue = $value;
        return $this;
    }

    public function getRequired(): bool
    {
        return $this->required;
    }

    public function setRequired(bool $value): InputPropertyInterface
    {
        $this->required = $value;
        return $this;
    }

    public function getDepend(): array
    {
        return $this->depend;
    }

    public function setDepend(array $value): InputPropertyInterface
    {
        $this->depend = $value;
        return $this;
    }
}
