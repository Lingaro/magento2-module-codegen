<?php

namespace Orba\Magento2Codegen\Model\Property\Traits;

use Orba\Magento2Codegen\Model\Property\Interfaces\RequiredInterface;

/**
 * Trait DependantTrait
 * @package Orba\Magento2Codegen\Model\Property\Traits
 */
trait RequiredTrait
{
    /**
     * @var bool
     */
    protected $required = false;

    /**
     * @param string $value
     * @return $this|RequiredInterface
     */
    public function setRequired(string $value): RequiredInterface
    {
        $this->required = (bool)$value;
        return $this;
    }

    /**
     * @return bool
     */
    public function getRequired(): bool
    {
        return $this->required;
    }
}
