<?php

namespace Orba\Magento2Codegen\Service\PropertyFactory;

use InvalidArgumentException;
use Orba\Magento2Codegen\Model\ConstProperty;
use Orba\Magento2Codegen\Model\PropertyInterface;

class ConstFactory implements FactoryInterface
{
    public function create(string $name, array $config): PropertyInterface
    {
        if (empty($name)) {
            throw new InvalidArgumentException('Name cannot be empty.');
        }
        if (!isset($config['value'])) {
            throw new InvalidArgumentException('Value must be set for const property.');
        }
        return (new ConstProperty())->setName($name)->setValue($config['value']);
    }
}