<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Orba\Magento2Codegen\Service\PropertyFactory;

use Orba\Magento2Codegen\Service\PropertyBuilder;

class AbstractFactory
{
    protected PropertyBuilder $propertyBuilder;

    public function __construct(PropertyBuilder $propertyBuilder)
    {
        $this->propertyBuilder = $propertyBuilder;
    }
}
