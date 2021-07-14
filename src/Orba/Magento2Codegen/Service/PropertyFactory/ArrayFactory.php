<?php

/**
 * @copyright Copyright © 2021 Orba. All rights reserved.
 * @author    info@orba.co
 */

declare(strict_types=1);

namespace Orba\Magento2Codegen\Service\PropertyFactory;

use Orba\Magento2Codegen\Model\ArrayProperty;
use Orba\Magento2Codegen\Model\PropertyInterface;
use Orba\Magento2Codegen\Service\PropertyFactory;
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
