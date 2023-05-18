<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Orba\Magento2Codegen\Service\PropertyValueCollector\Console;

use InvalidArgumentException;
use Orba\Magento2Codegen\Model\BooleanProperty;
use Orba\Magento2Codegen\Model\InputPropertyInterface;
use Orba\Magento2Codegen\Model\PropertyInterface;
use Orba\Magento2Codegen\Util\PropertyBag;

class BooleanCollector extends AbstractConsoleCollector
{
    protected string $propertyType = BooleanProperty::class;

    protected function collectValueFromInput(InputPropertyInterface $property, PropertyBag $propertyBag): bool
    {
        return (bool) $this->io->getInstance()
            ->confirm($this->questionPrefix . $property->getName(), $property->getDefaultValue());
    }
}
