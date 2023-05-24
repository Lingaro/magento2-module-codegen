<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Lingaro\Magento2Codegen\Service\PropertyValueCollector\Console;

use InvalidArgumentException;
use Lingaro\Magento2Codegen\Model\BooleanProperty;
use Lingaro\Magento2Codegen\Model\InputPropertyInterface;
use Lingaro\Magento2Codegen\Model\PropertyInterface;
use Lingaro\Magento2Codegen\Util\PropertyBag;

class BooleanCollector extends AbstractConsoleCollector
{
    protected string $propertyType = BooleanProperty::class;

    protected function collectValueFromInput(InputPropertyInterface $property, PropertyBag $propertyBag): bool
    {
        return (bool) $this->io->getInstance()
            ->confirm($this->questionPrefix . $property->getName(), $property->getDefaultValue());
    }
}
