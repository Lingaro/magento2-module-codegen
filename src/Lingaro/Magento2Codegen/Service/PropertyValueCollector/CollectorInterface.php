<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Lingaro\Magento2Codegen\Service\PropertyValueCollector;

use Lingaro\Magento2Codegen\Model\PropertyInterface;
use Lingaro\Magento2Codegen\Util\PropertyBag;

interface CollectorInterface
{
    /**
     * @return mixed
     */
    public function collectValue(PropertyInterface $property, PropertyBag $propertyBag);
}
