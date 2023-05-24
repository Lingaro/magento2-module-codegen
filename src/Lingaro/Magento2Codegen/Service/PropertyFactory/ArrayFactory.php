<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Lingaro\Magento2Codegen\Service\PropertyFactory;

use Lingaro\Magento2Codegen\Model\ArrayProperty;
use Lingaro\Magento2Codegen\Model\PropertyInterface;
use Lingaro\Magento2Codegen\Service\PropertyFactory;
use RuntimeException;

use function is_null;

class ArrayFactory extends AbstractFactory implements FactoryInterface
{
    private ?PropertyFactory $propertyFactory = null;

    public function create(string $name, array $config): PropertyInterface
    {
        if (is_null($this->propertyFactory)) {
            throw new RuntimeException('Property factory is unset.');
        }
        $property = new ArrayProperty();
        $this->propertyBuilder
            ->addName($property, $name)
            ->addDescription($property, $config)
            ->addDepend($property, $config)
            ->addRequired($property, $config)
            ->addChildren($property, $this->propertyFactory, $config);
        return $property;
    }

    public function setPropertyFactory(PropertyFactory $propertyFactory): void
    {
        $this->propertyFactory = $propertyFactory;
    }
}
