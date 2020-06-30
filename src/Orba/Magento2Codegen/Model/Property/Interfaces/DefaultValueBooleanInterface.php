<?php

namespace Orba\Magento2Codegen\Model\Property\Interfaces;

/**
 * Interface DefaultValueBooleanInterface
 * @package Orba\Magento2Codegen\Model\Property\Interfaces
 */
interface DefaultValueBooleanInterface
{
    /**
     * @return bool
     */
    public function getDefaultValue(): bool;

    /**
     * @param $value
     * @return DefaultValueBooleanInterface
     */
    public function setDefaultValue($value): DefaultValueBooleanInterface;
}