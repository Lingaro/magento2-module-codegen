<?php

namespace Orba\Magento2Codegen\Model;

use Orba\Magento2Codegen\Model\Property\Interfaces\DependantInterface as PropertyDependantInterface;
use Orba\Magento2Codegen\Model\Property\Interfaces\ValueInterface as PropertyValueInterface;
use Orba\Magento2Codegen\Model\Property\Traits\DependantTrait as PropertyDependantTrait;
use Orba\Magento2Codegen\Model\Property\Traits\ValueTrait as PropertyValueTrait;

/**
 * Class ConstProperty
 * @package Orba\Magento2Codegen\Model
 */
class ConstProperty extends AbstractProperty implements PropertyDependantInterface, PropertyValueInterface
{
    use PropertyDependantTrait, PropertyValueTrait;

    const TYPE = 'const';
}