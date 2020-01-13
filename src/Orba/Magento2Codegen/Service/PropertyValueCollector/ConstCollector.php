<?php

namespace Orba\Magento2Codegen\Service\PropertyValueCollector;

use InvalidArgumentException;
use Orba\Magento2Codegen\Model\ConstProperty;
use Orba\Magento2Codegen\Model\PropertyInterface;

class ConstCollector extends AbstractCollector
{
    protected function validateProperty(PropertyInterface $property): void
    {
        if (!$property instanceof ConstProperty) {
            throw new InvalidArgumentException('Invalid property type.');
        }
    }

    protected function _collectValue(PropertyInterface $property)
    {
        /** @var ConstProperty $property */
        return $property->getValue();
    }
}