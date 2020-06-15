<?php

namespace Orba\Magento2Codegen\Model;

use InvalidArgumentException;

class ChoiceProperty extends AbstractProperty
{
    /**
     * @var string|null
     */
    protected $defaultValue;

    /**
     * @var string[]
     */
    protected $options = [];

    public function setDefaultValue(string $value): ChoiceProperty
    {
        if (!in_array($value, $this->options)) {
            throw new InvalidArgumentException('Default value must exist in options array.');
        }
        $this->defaultValue = $value;
        return $this;
    }

    public function getDefaultValue():? string
    {
        return $this->defaultValue;
    }

    /**
     * @param string[] $options
     * @return ChoiceProperty
     */
    public function setOptions(array $options): ChoiceProperty
    {
        if (empty($options)) {
            throw new InvalidArgumentException('Options array cannot be empty.');
        }
        foreach ($options as $option) {
            if (!is_string($option)) {
                throw new InvalidArgumentException('All options must be strings.');
            }
        }
        $this->options = $options;
        return $this;
    }

    /**
     * @return string[]
     */
    public function getOptions(): array
    {
        return $this->options;
    }
}
