<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Orba\Magento2Codegen\Service\PropertyFactory;

use Orba\Magento2Codegen\Model\PropertyInterface;
use Orba\Magento2Codegen\Model\StringProperty;
use Orba\Magento2Codegen\Service\PropertyBuilder;
use Orba\Magento2Codegen\Service\PropertyStringValidatorsAdder;

class StringFactory extends AbstractFactory implements FactoryInterface
{
    private PropertyStringValidatorsAdder $validatorsAdder;

    public function __construct(PropertyBuilder $propertyBuilder, PropertyStringValidatorsAdder $validatorsAdder)
    {
        parent::__construct($propertyBuilder);
        $this->validatorsAdder = $validatorsAdder;
    }

    public function create(string $name, array $config): PropertyInterface
    {
        $property = new StringProperty();
        $this->propertyBuilder
            ->addName($property, $name)
            ->addDescription($property, $config)
            ->addDepend($property, $config)
            ->addRequired($property, $config)
            ->addDefaultValue($property, $config);
        $this->validatorsAdder->execute($property, $config);
        return $property;
    }
}
