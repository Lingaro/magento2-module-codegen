<?php

namespace Orba\Magento2Codegen\Model\Property\Traits;

use Orba\Magento2Codegen\Model\Property\Interfaces\DependantInterface;

/**
 * Trait DependantTrait
 * @package Orba\Magento2Codegen\Model\Property\Traits
 */
trait DependantTrait
{
    /**
     * @var array|null
     */
    protected $depend;

    /**
     * @param array|null $value
     * @return $this|DependantInterface
     */
    public function setDepend(?array $value): DependantInterface
    {
        $this->depend = $value;
        return $this;
    }

    public function getDepend(): ?array
    {
        return $this->depend;
    }
}
