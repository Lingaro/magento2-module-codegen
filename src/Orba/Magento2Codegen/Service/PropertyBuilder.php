<?php

/**
 * @copyright Copyright © 2021 Orba. All rights reserved.
 * @author    info@orba.co
 */

declare(strict_types=1);

namespace Orba\Magento2Codegen\Service;

use InvalidArgumentException;
use Orba\Magento2Codegen\Model\ArrayProperty;
use Orba\Magento2Codegen\Model\BooleanProperty;
use Orba\Magento2Codegen\Model\ChoiceProperty;
use Orba\Magento2Codegen\Model\ConstProperty;
use Orba\Magento2Codegen\Model\InputPropertyInterface;
use Orba\Magento2Codegen\Model\PropertyInterface;
use Orba\Magento2Codegen\Model\StringProperty;

use function is_array;
use function is_bool;
use function is_string;

class PropertyBuilder
{
    public function addName(PropertyInterface $property, string $name): self
    {
        if (empty($name)) {
            throw new InvalidArgumentException('Name cannot be empty.');
        }
        if (!preg_match('/^[a-zA-Z_\x80-\xff][a-zA-Z0-9_\x80-\xff]*$/', $name)) {
            throw new InvalidArgumentException('Invalid name.');
        }
        $property->setName($name);
        return $this;
    }

    public function addDescription(PropertyInterface $property, array $config): self
    {
        if (isset($config['description'])) {
            if (!is_string($config['description'])) {
                throw new InvalidArgumentException('Description must be a string.');
            }
            $property->setDescription($config['description']);
        }
        return $this;
    }

    public function addDepend(InputPropertyInterface $property, array $config): self
    {
        if (isset($config['depend'])) {
            if (!is_array($config['depend'])) {
                throw new InvalidArgumentException('Depend must be an array.');
            }
            foreach (array_keys($config['depend']) as $key) {
                if (!is_string($key)) {
                    throw new InvalidArgumentException('All keys in depend array must be strings.');
                }
                if (is_array($config['depend'][$key])) {
                    throw new InvalidArgumentException('Depend array cannot be multidimensional.');
                }
            }
            $property->setDepend($config['depend']);
        }
        return $this;
    }

    public function addRequired(InputPropertyInterface $property, array $config): self
    {
        if (isset($config['required'])) {
            if (!is_bool($config['required'])) {
                throw new InvalidArgumentException('Required must be boolean.');
            }
            $property->setRequired($config['required']);
        }
        return $this;
    }

    public function addDefaultValue(InputPropertyInterface $property, array $config): self
    {
        if (isset($config['default'])) {
            $this->validatePropertyForDefaultValue($property, $config);
            $property->setDefaultValue($config['default']);
        }
        return $this;
    }

    public function addChildren(ArrayProperty $property, PropertyFactory $propertyFactory, array $config): self
    {
        if (!isset($config['children'])) {
            throw new InvalidArgumentException('Children must be set for array property.');
        }
        if (!is_array($config['children'])) {
            throw new InvalidArgumentException('Children must be an array.');
        }
        if (empty($config['children'])) {
            throw new InvalidArgumentException('Children array cannot be empty.');
        }
        $children = [];
        foreach ($config['children'] as $childName => $childConfig) {
            if (!is_array($childConfig)) {
                throw new InvalidArgumentException('Child config must be an array.');
            }
            $children[] = $propertyFactory->create($childName, $childConfig);
        }
        $property->setChildren($children);
        return $this;
    }

    public function addValue(ConstProperty $property, array $config): self
    {
        if (!isset($config['value'])) {
            throw new InvalidArgumentException('Value must be set.');
        }
        $property->setValue($config['value']);
        return $this;
    }

    public function addOptions(ChoiceProperty $property, array $config): self
    {
        if (!isset($config['options'])) {
            throw new InvalidArgumentException('Options must be set for choice property.');
        }
        if (!is_array($config['options'])) {
            throw new InvalidArgumentException('Options must be an array.');
        }
        if (empty($config['options'])) {
            throw new InvalidArgumentException('Options array cannot be empty.');
        }
        foreach ($config['options'] as $option) {
            if (!is_string($option)) {
                throw new InvalidArgumentException('All options must be strings.');
            }
        }
        $property->setOptions($config['options']);
        return $this;
    }

    private function validatePropertyForDefaultValue(InputPropertyInterface $property, array $config): void
    {
        if ($property instanceof ChoiceProperty) {
            if (empty($property->getOptions())) {
                throw new InvalidArgumentException('Array of options must be set before setting default value.');
            }
            if (!in_array($config['default'], $property->getOptions())) {
                throw new InvalidArgumentException('Default value must exist in options array.');
            }
            return;
        }
        if ($property instanceof StringProperty) {
            if (!is_string($config['default'])) {
                throw new InvalidArgumentException('Default value must be a string.');
            }
            return;
        }
        if ($property instanceof BooleanProperty) {
            if (!is_bool($config['default'])) {
                throw new InvalidArgumentException('Default value must be boolean.');
            }
            return;
        }
        throw new InvalidArgumentException('Setting default value is not supported for this property.');
    }
}
