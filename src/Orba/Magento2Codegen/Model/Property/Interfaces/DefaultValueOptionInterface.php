<?php

namespace Orba\Magento2Codegen\Model\Property\Interfaces;

/**
 * Interface DefaultValueOptionInterface
 * @package Orba\Magento2Codegen\Model\Property\Interfaces
 */
interface DefaultValueOptionInterface extends DefaultValueStringInterface
{
    /**
     * @return string[]
     */
    public function getOptions(): array;

    /**
     * @param array $options
     * @return $this|DefaultValueOptionInterface
     */
    public function setOptions(array $options): DefaultValueOptionInterface;
}