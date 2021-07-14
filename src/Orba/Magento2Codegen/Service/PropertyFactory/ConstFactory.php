<?php

/**
 * @copyright Copyright © 2021 Orba. All rights reserved.
 * @author    info@orba.co
 */

declare(strict_types=1);

namespace Orba\Magento2Codegen\Service\PropertyFactory;

use Orba\Magento2Codegen\Model\ConstProperty;
use Orba\Magento2Codegen\Model\PropertyInterface;

class ConstFactory extends AbstractFactory implements FactoryInterface
{
    public function create(string $name, array $config): PropertyInterface
    {
        $property = new ConstProperty();
        $this->propertyBuilder
            ->addName($property, $name)
            ->addDescription($property, $config)
            ->addValue($property, $config);
        return $property;
    }
}
