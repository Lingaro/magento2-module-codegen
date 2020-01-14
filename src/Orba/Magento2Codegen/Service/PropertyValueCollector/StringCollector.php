<?php

namespace Orba\Magento2Codegen\Service\PropertyValueCollector;

use InvalidArgumentException;
use Orba\Magento2Codegen\Model\PropertyInterface;
use Orba\Magento2Codegen\Model\StringProperty;

class StringCollector extends AbstractInputCollector
{
    protected function validateProperty(PropertyInterface $property): void
    {
        if (!$property instanceof StringProperty) {
            throw new InvalidArgumentException('Invalid property type.');
        }
    }

    protected function collectValueFromInput(PropertyInterface $property)
    {
        /** @var StringProperty $property */
        return $this->io->getInstance()
            ->ask($this->questionPrefix . $property->getName(), $property->getDefaultValue());
    }
}