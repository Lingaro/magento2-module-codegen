<?php

/**
 * @copyright Copyright © 2021 Orba. All rights reserved.
 * @author    info@orba.co
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
