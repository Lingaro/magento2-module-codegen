<?php

namespace Orba\Magento2Codegen\Service\PropertyFactory;

use Orba\Magento2Codegen\Model\BooleanProperty;
use Orba\Magento2Codegen\Model\PropertyInterface;

class BooleanFactory extends AbstractFactory implements FactoryInterface
{
    public function create(string $name, array $config): PropertyInterface
    {
        $property = new BooleanProperty();
        $this->propertyBuilder
            ->addName($property, $name)
            ->addDescription($property, $config)
            ->addDepend($property, $config)
            ->addDefaultValue($property, $config);
        return $property;
    }
}
