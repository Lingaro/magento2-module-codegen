<?php

namespace Orba\Magento2Codegen\Service\PropertyFactory;

use InvalidArgumentException;
use Orba\Magento2Codegen\Model\BooleanProperty;
use Orba\Magento2Codegen\Model\PropertyInterface;

class BooleanFactory implements FactoryInterface
{
    public function create(string $name, array $config): PropertyInterface
    {
        if (empty($name)) {
            throw new InvalidArgumentException('Name cannot be empty.');
        }
        $property = (new BooleanProperty())->setName($name);
        if (isset($config['description'])) {
            $property->setDescription($config['description']);
        }
        if (isset($config['default'])) {
            $property->setDefaultValue($config['default']);
        }
        return $property;
    }
}
