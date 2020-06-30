<?php

namespace Orba\Magento2Codegen\Model;

use Orba\Magento2Codegen\Model\Property\Interfaces\DefaultValueStringInterface as PropertyDefaultValueStringInterface;
use Orba\Magento2Codegen\Model\Property\Interfaces\DependantInterface as PropertyDependantInterface;
use Orba\Magento2Codegen\Model\Property\Interfaces\RequiredInterface as PropertyRequiredInterface;
use Orba\Magento2Codegen\Model\Property\Traits\DefaultValueStringTrait as PropertyDefaultValueStringTrait;
use Orba\Magento2Codegen\Model\Property\Traits\DependantTrait as PropertyDependantTrait;
use Orba\Magento2Codegen\Model\Property\Traits\RequiredTrait as PropertyRequiredTrait;

/**
 * Class StringProperty
 * @package Orba\Magento2Codegen\Model
 */
class StringProperty extends AbstractProperty implements PropertyDefaultValueStringInterface, PropertyDependantInterface, PropertyRequiredInterface
{
    use PropertyDefaultValueStringTrait, PropertyDependantTrait, PropertyRequiredTrait;

    const TYPE = 'string';
}