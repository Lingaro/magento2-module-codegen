<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Lingaro\Magento2Codegen\Service\PropertyFactory;

use Lingaro\Magento2Codegen\Model\ChoiceProperty;
use Lingaro\Magento2Codegen\Model\PropertyInterface;

class ChoiceFactory extends AbstractFactory implements FactoryInterface
{
    public function create(string $name, array $config): PropertyInterface
    {
        $property = new ChoiceProperty();
        $this->propertyBuilder
            ->addName($property, $name)
            ->addDescription($property, $config)
            ->addDepend($property, $config)
            ->addOptions($property, $config)
            ->addDefaultValue($property, $config);
        return $property;
    }
}
