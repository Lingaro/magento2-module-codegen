<?php

/**
 * @copyright Copyright © 2021 Orba. All rights reserved.
 * @author    info@orba.co
 */

declare(strict_types=1);

namespace Orba\Magento2Codegen\Service\PropertyValueCollector;

use Orba\Magento2Codegen\Model\PropertyInterface;
use Orba\Magento2Codegen\Util\PropertyBag;

abstract class AbstractCollector implements CollectorInterface
{
    public function collectValue(PropertyInterface $property, PropertyBag $propertyBag)
    {
        $this->validateProperty($property);
        return $this->internalCollectValue($property, $propertyBag);
    }

    abstract protected function validateProperty(PropertyInterface $property): void;

    /**
     * @return mixed
     */
    abstract protected function internalCollectValue(PropertyInterface $property, PropertyBag $propertyBag);
}
