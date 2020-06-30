<?php

namespace Orba\Magento2Codegen\Service;

use InvalidArgumentException;
use Orba\Magento2Codegen\Model\Property\Interfaces\ChildrenInterface as PropertyChildrenInterface;
use Orba\Magento2Codegen\Model\Property\Interfaces\DefaultValueBooleanInterface as PropertyDefaultValueBooleanInterface;
use Orba\Magento2Codegen\Model\Property\Interfaces\DefaultValueOptionInterface as PropertyDefaultValueOptionInterface;
use Orba\Magento2Codegen\Model\Property\Interfaces\DefaultValueStringInterface as PropertyDefaultValueStringInterface;
use Orba\Magento2Codegen\Model\Property\Interfaces\DependantInterface as PropertyDependantInterface;
use Orba\Magento2Codegen\Model\Property\Interfaces\RequiredInterface as PropertyRequiredInterface;
use Orba\Magento2Codegen\Model\Property\Interfaces\ValueInterface as PropertyValueInterface;
use Orba\Magento2Codegen\Model\PropertyInterface;

/**
 * Class PropertyBuilder
 * @package Orba\Magento2Codegen\Service
 */
class PropertyBuilder
{
    /**
     * @param PropertyInterface $property
     * @param $name
     * @return $this
     */
    public function addName(PropertyInterface $property, $name): self
    {
        if (empty($name)) {
            throw new InvalidArgumentException('Name cannot be empty.');
        }
        $property->setName($name);
        return $this;
    }

    /**
     * @param PropertyInterface $property
     * @param array $config
     * @return $this
     */
    public function addDescription(PropertyInterface $property, array $config): self
    {
        if (isset($config['description'])) {
            $property->setDescription($config['description']);
        }
        return $this;
    }

    /**
     * @param PropertyDependantInterface $property
     * @param array $config
     * @return $this
     */
    public function addDependant(PropertyDependantInterface $property, array $config): self
    {
        if (isset($config['depend'])) {
            $property->setDepend($config['depend']);
        }
        return $this;
    }

    /**
     * @param PropertyRequiredInterface $property
     * @param array $config
     * @return $this
     */
    public function addRequired(PropertyRequiredInterface $property, array $config): self
    {
        if (isset($config['required'])) {
            $property->setRequired($config['required']);
        }
        return $this;
    }

    /**
     * @param PropertyChildrenInterface $property
     * @param PropertyFactory $propertyFactory
     * @param array $config
     * @return $this
     */
    public function addChildren(
        PropertyChildrenInterface $property,
        PropertyFactory $propertyFactory,
        array $config
    ): self {
        if (!isset($config['children']) || empty($config['children'])) {
            throw new InvalidArgumentException('Array property must contain children.');
        }

        $children = [];
        foreach ($config['children'] as $childName => $childConfig) {
            $children[] = $propertyFactory->create($childName, $childConfig);
        }
        $property->setChildren($children);
        return $this;
    }

    /**
     * @param PropertyValueInterface $property
     * @param array $config
     * @return $this
     */
    public function addValue(PropertyValueInterface $property, array $config): self
    {
        if (!isset($config['value'])) {
            throw new InvalidArgumentException('Value must be set for const property.');
        }
        $property->setValue($config['value']);
        return $this;
    }

    /**
     * @param PropertyInterface $property
     * @param array $config
     * @return $this
     */
    public function addDefaultValue(PropertyInterface $property, array $config): self
    {
        if ($property instanceof PropertyDefaultValueOptionInterface) {
            if (empty($config['options'])) {
                throw new InvalidArgumentException('Array of options is required.');
            }
            $property->setOptions($config['options']);
        }
        if ($property instanceof PropertyDefaultValueBooleanInterface
            || $property instanceof PropertyDefaultValueStringInterface) {
            if (isset($config['default'])) {
                $property->setDefaultValue($config['default']);
            }
        }
        return $this;
    }
}