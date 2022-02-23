<?php

/**
 * @copyright Copyright © 2022 Orba. All rights reserved.
 * @author    info@orba.co
 */

declare(strict_types=1);

namespace Orba\Magento2Codegen\Service\PropertyValueCollector\Yaml;

use InvalidArgumentException;
use Orba\Magento2Codegen\Model\BooleanProperty;
use Orba\Magento2Codegen\Model\InputPropertyInterface;
use Orba\Magento2Codegen\Util\PropertyBag;

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
