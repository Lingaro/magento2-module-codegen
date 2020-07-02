<?php

namespace Orba\Magento2Codegen\Service\PropertyFactory;

use Orba\Magento2Codegen\Model\ArrayProperty;
use Orba\Magento2Codegen\Model\PropertyInterface;
use Orba\Magento2Codegen\Service\PropertyFactory;
use RuntimeException;

class ArrayFactory extends AbstractFactory implements FactoryInterface
{
    /**
     * @var PropertyFactory
     */
    private $propertyFactory;

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

    /**
     * @param PropertyFactory $propertyFactory
     */
    public function setPropertyFactory(PropertyFactory $propertyFactory): void
    {
        $this->propertyFactory = $propertyFactory;
    }
}
