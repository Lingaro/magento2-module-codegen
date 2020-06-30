<?php

namespace Orba\Magento2Codegen\Model;

use Orba\Magento2Codegen\Model\Property\Interfaces\DefaultValueOptionInterface as PropertyDefaultValueOptionInterface;
use Orba\Magento2Codegen\Model\Property\Interfaces\DependantInterface as PropertyDependantInterface;
use Orba\Magento2Codegen\Model\Property\Traits\DefaultValueOptionTrait as PropertyDefaultValueOptionTrait;
use Orba\Magento2Codegen\Model\Property\Traits\DependantTrait as PropertyDependantTrait;

/**
 * Class ChoiceProperty
 * @package Orba\Magento2Codegen\Model
 */
class ChoiceProperty extends AbstractProperty implements PropertyDefaultValueOptionInterface, PropertyDependantInterface
{
    use PropertyDefaultValueOptionTrait, PropertyDependantTrait;
}
