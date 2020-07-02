<?php

namespace Orba\Magento2Codegen\Model;

/**
 * Class ChoiceProperty
 * @package Orba\Magento2Codegen\Model
 */
class ChoiceProperty extends AbstractInputProperty
{
    /**
     * @var string[]
     */
    protected $options = [];

    /**
     * @return string[]
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @param string[] $options
     * @return $this
     */
    public function setOptions(array $options): self
    {
        $this->options = $options;
        return $this;
    }
}
