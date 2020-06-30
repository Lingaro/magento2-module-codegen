<?php

namespace Orba\Magento2Codegen\Model\Property\Traits;

use InvalidArgumentException;
use Orba\Magento2Codegen\Model\Property\Interfaces\DefaultValueOptionInterface;
use Orba\Magento2Codegen\Model\Property\Interfaces\DefaultValueStringInterface;

/**
 * Trait DefaultValueOptionTrait
 * @package Orba\Magento2Codegen\Model\Property\Traits
 */
trait DefaultValueOptionTrait
{
    /**
     * @var string|null
     */
    protected $defaultValue;

    /**
     * @var string[]
     */
    protected $options = [];

    /**
     * @return string|null
     */
    public function getDefaultValue(): ?string
    {
        return $this->defaultValue;
    }

    /**
     * @param string $value
     * @return $this|DefaultValueStringInterface
     */
    public function setDefaultValue(string $value): DefaultValueStringInterface
    {
        if (!in_array($value, $this->options)) {
            throw new InvalidArgumentException('Default value must exist in options array.');
        }
        $this->defaultValue = $value;
        return $this;
    }

    /**
     * @return string[]
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @param array $options
     * @return $this|DefaultValueOptionInterface
     */
    public function setOptions(array $options): DefaultValueOptionInterface
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
}
