<?php

namespace Orba\Magento2Codegen\Service\PropertyFactory;

use InvalidArgumentException;
use Orba\Magento2Codegen\Model\ArrayProperty;
use Orba\Magento2Codegen\Model\PropertyInterface;
use Orba\Magento2Codegen\Service\PropertyFactory;
use RuntimeException;

class ArrayFactory implements FactoryInterface
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
        if (empty($name)) {
            throw new InvalidArgumentException('Name cannot be empty.');
        }
        if (!isset($config['children']) || empty($config['children'])) {
            throw new InvalidArgumentException('Array property must contain children.');
        }
        $property = (new ArrayProperty())->setName($name);
        if (isset($config['description'])) {
            $property->setDescription($config['description']);
        }
        if (isset($config['depend'])) {
            $property->setDepend($config['depend']);
        }
        if (isset($config['required'])) {
            $property->setRequired($config['required']);
        }
        $children = [];
        foreach ($config['children'] as $childName => $childConfig) {
            $children[] = $this->propertyFactory->create($childName, $childConfig);
        }
        $property->setChildren($children);
        return $property;
    }

    public function setPropertyFactory(PropertyFactory $propertyFactory): void
    {
        $this->propertyFactory = $propertyFactory;
    }
}
