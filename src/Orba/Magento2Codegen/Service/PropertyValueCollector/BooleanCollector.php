<?php

/**
 * @copyright Copyright © 2021 Orba. All rights reserved.
 * @author    info@orba.co
 */

declare(strict_types=1);

namespace Orba\Magento2Codegen\Service\PropertyValueCollector;

use InvalidArgumentException;
use Orba\Magento2Codegen\Model\BooleanProperty;
use Orba\Magento2Codegen\Model\InputPropertyInterface;
use Orba\Magento2Codegen\Model\PropertyInterface;
use Orba\Magento2Codegen\Util\PropertyBag;

class BooleanCollector extends AbstractInputCollector
{
    protected function validateProperty(PropertyInterface $property): void
    {
        if (!$property instanceof BooleanProperty) {
            throw new InvalidArgumentException('Invalid property type.');
        }
    }

    protected function collectValueFromInput(InputPropertyInterface $property, PropertyBag $propertyBag): bool
    {
        return (bool) $this->io->getInstance()
            ->confirm($this->questionPrefix . $property->getName(), $property->getDefaultValue());
    }
}
