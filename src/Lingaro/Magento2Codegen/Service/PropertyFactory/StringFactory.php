<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Lingaro\Magento2Codegen\Service\PropertyFactory;

use Lingaro\Magento2Codegen\Model\PropertyInterface;
use Lingaro\Magento2Codegen\Model\StringProperty;
use Lingaro\Magento2Codegen\Service\PropertyBuilder;
use Lingaro\Magento2Codegen\Service\PropertyStringValidatorsAdder;

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
