<?php

/**
 * @copyright Copyright © 2022 Orba. All rights reserved.
 * @author    info@orba.co
 */

namespace Orba\Magento2Codegen\Service\PropertyValueCollector;

use InvalidArgumentException;
use Orba\Magento2Codegen\Model\ConstProperty;
use Orba\Magento2Codegen\Model\PropertyInterface;
use Orba\Magento2Codegen\Util\PropertyBag;

class ConstCollector extends AbstractCollector
{
    protected string $propertyType = ConstProperty::class;

    protected function internalCollectValue(PropertyInterface $property, PropertyBag $propertyBag)
    {
        /** @var ConstProperty $property */
        return $property->getValue();
    }
}
