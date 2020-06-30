<?php

namespace Orba\Magento2Codegen\Model;

use Orba\Magento2Codegen\Model\Property\Interfaces\DefaultValueBooleanInterface as PropertyDefaultValueBooleanInterface;
use Orba\Magento2Codegen\Model\Property\Interfaces\DependantInterface as PropertyDependantInterface;
use Orba\Magento2Codegen\Model\Property\Traits\DefaultValueBooleanTrait as PropertyDefaultValueBooleanTrait;
use Orba\Magento2Codegen\Model\Property\Traits\DependantTrait as PropertyDependantTrait;


/**
 * Class BooleanProperty
 * @package Orba\Magento2Codegen\Model
 */
class BooleanProperty extends AbstractProperty implements PropertyDefaultValueBooleanInterface, PropertyDependantInterface
{
    use PropertyDefaultValueBooleanTrait, PropertyDependantTrait;
}
