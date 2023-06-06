<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

namespace Lingaro\Magento2Codegen\Service\PropertyValueCollector;

use InvalidArgumentException;
use Lingaro\Magento2Codegen\Model\ConstProperty;
use Lingaro\Magento2Codegen\Model\PropertyInterface;
use Lingaro\Magento2Codegen\Util\PropertyBag;

class ConstCollector extends AbstractCollector
{
    protected string $propertyType = ConstProperty::class;

    protected function internalCollectValue(PropertyInterface $property, PropertyBag $propertyBag)
    {
        /** @var ConstProperty $property */
        return $property->getValue();
    }
}
