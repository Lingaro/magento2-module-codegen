<?php

/**
 * @copyright Copyright © 2021 Orba. All rights reserved.
 * @author    info@orba.co
 */

declare(strict_types=1);

namespace Orba\Magento2Codegen\Service\PropertyValueCollector;

use Orba\Magento2Codegen\Model\PropertyInterface;
use Orba\Magento2Codegen\Util\PropertyBag;

interface CollectorInterface
{
    /**
     * @return mixed
     */
    public function collectValue(PropertyInterface $property, PropertyBag $propertyBag);
}
