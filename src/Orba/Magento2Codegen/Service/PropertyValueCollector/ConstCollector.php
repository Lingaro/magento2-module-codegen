<?php

namespace Orba\Magento2Codegen\Service\PropertyValueCollector;

use InvalidArgumentException;
use Orba\Magento2Codegen\Model\ConstProperty;
use Orba\Magento2Codegen\Model\PropertyInterface;
use Orba\Magento2Codegen\Util\PropertyBag;

class ConstCollector extends AbstractCollector
{
    protected function validateProperty(PropertyInterface $property): void
    {
        if (!$property instanceof ConstProperty) {
            throw new InvalidArgumentException('Invalid property type.');
        }
    }

    protected function internalCollectValue(PropertyInterface $property, PropertyBag $propertyBag)
    {
        /** @var ConstProperty $property */
        return $property->getValue();
    }
}
