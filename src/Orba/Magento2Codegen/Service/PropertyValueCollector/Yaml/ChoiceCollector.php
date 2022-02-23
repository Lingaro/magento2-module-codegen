<?php

/**
 * @copyright Copyright © 2022 Orba. All rights reserved.
 * @author    info@orba.co
 */

declare(strict_types=1);

namespace Orba\Magento2Codegen\Service\PropertyValueCollector\Yaml;

use Orba\Magento2Codegen\Exception\ValueNotAllowedException;
use Orba\Magento2Codegen\Exception\ValueNotSetException;
use Orba\Magento2Codegen\Model\ChoiceProperty;
use Orba\Magento2Codegen\Model\InputPropertyInterface;
use Orba\Magento2Codegen\Util\PropertyBag;

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
