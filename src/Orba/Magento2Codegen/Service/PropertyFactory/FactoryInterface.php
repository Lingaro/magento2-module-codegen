<?php

namespace Orba\Magento2Codegen\Service\PropertyFactory;

use Orba\Magento2Codegen\Model\PropertyInterface;

interface FactoryInterface
{
    public function create(string $name, array $config): PropertyInterface;
}