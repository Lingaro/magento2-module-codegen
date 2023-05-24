<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Lingaro\Magento2Codegen\Service\PropertyValueCollector\Yaml;

use Lingaro\Magento2Codegen\Exception\ValueNotAllowedException;
use Lingaro\Magento2Codegen\Exception\ValueNotSetException;
use Lingaro\Magento2Codegen\Model\ChoiceProperty;
use Lingaro\Magento2Codegen\Model\InputPropertyInterface;
use Lingaro\Magento2Codegen\Util\PropertyBag;

use function in_array;

class ChoiceCollector extends AbstractYamlCollector
{
    protected string $propertyType = ChoiceProperty::class;

    protected function collectValueFromInput(InputPropertyInterface $property, PropertyBag $propertyBag): string
    {
        /** @var ChoiceProperty $property */
        $key = $this->keyPrefix . $property->getName();
        $value = $this->dataProvider->get($key, $property->getDefaultValue());
        if ($property->getRequired() && $value === null) {
            throw new ValueNotSetException(sprintf('Value of "%s" must be set.', $key));
        }
        if ($value && !in_array($value, $property->getOptions())) {
            throw new ValueNotAllowedException(sprintf(
                'Value of "%s" must be one of the following: %s.',
                $key,
                implode(', ', $property->getOptions())
            ));
        }
        return (string) $value;
    }
}
