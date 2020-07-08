<?php

namespace Orba\Magento2Codegen\Service\PropertyFactory;

use Orba\Magento2Codegen\Model\IntegerProperty;
use Orba\Magento2Codegen\Model\PropertyInterface;

class IntegerFactory extends AbstractFactory implements FactoryInterface
{
    public function create(string $name, array $config): PropertyInterface
    {
        $property = new IntegerProperty();
        $this->propertyBuilder
            ->addName($property, $name)
            ->addDescription($property, $config)
            ->addDepend($property, $config)
            ->addRequired($property, $config)
            ->addDefaultValue($property, $config);
        return $property;
    }
}
