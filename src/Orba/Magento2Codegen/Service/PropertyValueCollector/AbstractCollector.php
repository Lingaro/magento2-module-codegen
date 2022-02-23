<?php

/**
 * @copyright Copyright © 2022 Orba. All rights reserved.
 * @author    info@orba.co
 */

declare(strict_types=1);

namespace Orba\Magento2Codegen\Service\PropertyValueCollector;

use InvalidArgumentException;
use Orba\Magento2Codegen\Model\PropertyInterface;
use Orba\Magento2Codegen\Util\PropertyBag;

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
