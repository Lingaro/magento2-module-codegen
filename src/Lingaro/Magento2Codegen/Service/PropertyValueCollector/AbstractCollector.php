<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Lingaro\Magento2Codegen\Service\PropertyValueCollector;

use InvalidArgumentException;
use Lingaro\Magento2Codegen\Model\PropertyInterface;
use Lingaro\Magento2Codegen\Util\PropertyBag;

abstract class AbstractCollector implements CollectorInterface
{
    protected string $propertyType;

    public function collectValue(PropertyInterface $property, PropertyBag $propertyBag)
    {
        if (!$property instanceof $this->propertyType) {
            throw new InvalidArgumentException('Invalid property type.');
        }
        return $this->internalCollectValue($property, $propertyBag);
    }

    /**
     * @return mixed
     */
    abstract protected function internalCollectValue(PropertyInterface $property, PropertyBag $propertyBag);
}
