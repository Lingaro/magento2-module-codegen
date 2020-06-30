<?php

namespace Orba\Magento2Codegen\Model;

use Orba\Magento2Codegen\Model\Property\Interfaces\ChildrenInterface as PropertyChildrenInterface;
use Orba\Magento2Codegen\Model\Property\Interfaces\DependantInterface as PropertyDependantInterface;
use Orba\Magento2Codegen\Model\Property\Interfaces\RequiredInterface as PropertyRequiredInterface;
use Orba\Magento2Codegen\Model\Property\Traits\ChildrenTrait as PropertyChildrenTrait;
use Orba\Magento2Codegen\Model\Property\Traits\DependantTrait as PropertyDependantTrait;
use Orba\Magento2Codegen\Model\Property\Traits\RequiredTrait as PropertyRequiredTrait;

/**
 * Class ArrayProperty
 * @package Orba\Magento2Codegen\Model
 */
class ArrayProperty extends AbstractProperty implements PropertyChildrenInterface, PropertyDependantInterface, PropertyRequiredInterface
{
    use PropertyChildrenTrait, PropertyDependantTrait, PropertyRequiredTrait;
}