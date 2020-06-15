<?php

namespace Orba\Magento2Codegen\Service\PropertyFactory;

use InvalidArgumentException;
use Orba\Magento2Codegen\Model\ChoiceProperty;
use Orba\Magento2Codegen\Model\PropertyInterface;

class ChoiceFactory implements FactoryInterface
{
    public function create(string $name, array $config): PropertyInterface
    {
        if (empty($name)) {
            throw new InvalidArgumentException('Name cannot be empty.');
        }
        if (empty($config['options'])) {
            throw new InvalidArgumentException('Array of options is required.');
        }
        $property = (new ChoiceProperty())->setName($name);
        if (isset($config['description'])) {
            $property->setDescription($config['description']);
        }
        $property->setOptions($config['options']);
        if (isset($config['default'])) {
            $property->setDefaultValue($config['default']);
        }
        return $property;
    }
}
