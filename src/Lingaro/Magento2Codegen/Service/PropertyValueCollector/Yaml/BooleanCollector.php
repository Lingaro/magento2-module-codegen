<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Lingaro\Magento2Codegen\Service\PropertyValueCollector\Yaml;

use InvalidArgumentException;
use Lingaro\Magento2Codegen\Model\BooleanProperty;
use Lingaro\Magento2Codegen\Model\InputPropertyInterface;
use Lingaro\Magento2Codegen\Util\PropertyBag;

class BooleanCollector extends AbstractYamlCollector
{
    protected string $propertyType = BooleanProperty::class;

    protected function collectValueFromInput(InputPropertyInterface $property, PropertyBag $propertyBag): bool
    {
        $key = $this->keyPrefix . $property->getName();
        $value = $this->dataProvider->get($key, $property->getDefaultValue());
        if (!is_bool($value)) {
            throw new InvalidArgumentException(sprintf('Value of "%s" must be either true or false.', $key));
        }
        return $value;
    }
}
