<?php

namespace Orba\Magento2Codegen\Service\PropertyFactory;

use InvalidArgumentException;
use Orba\Magento2Codegen\Model\PropertyInterface;
use Orba\Magento2Codegen\Model\StringProperty;

class StringFactory extends AbstractFactory implements FactoryInterface
{
    public function create(string $name, array $config): PropertyInterface
    {
        $property = new StringProperty();
        $this->propertyBuilder
            ->addName($property, $name)
            ->addDescription($property, $config)
            ->addDependant($property, $config)
            ->addRequired($property, $config)
            ->addDefaultValue($property, $config);
        return $property;
    }
}
