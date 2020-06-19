<?php

namespace Orba\Magento2Codegen\Service\PropertyFactory;

use InvalidArgumentException;
use Orba\Magento2Codegen\Model\PropertyInterface;
use Orba\Magento2Codegen\Model\StringProperty;

class StringFactory implements FactoryInterface
{
    public function create(string $name, array $config): PropertyInterface
    {
        if (empty($name)) {
            throw new InvalidArgumentException('Name cannot be empty.');
        }
        $property = (new StringProperty())->setName($name);
        if (isset($config['description'])) {
            $property->setDescription($config['description']);
        }
        if (isset($config['default'])) {
            $property->setDefaultValue($config['default']);
        }
        if (isset($config['required'])) {
            $property->setRequired($config['required']);
        }
        return $property;
    }
}