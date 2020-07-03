<?php

namespace Orba\Magento2Codegen\Service\PropertyFactory;

use Orba\Magento2Codegen\Service\PropertyBuilder;

class AbstractFactory
{
    /**
     * @var PropertyBuilder
     */
    protected $propertyBuilder;

    /**
     * AbstractFactory constructor.
     * @param PropertyBuilder $propertyBuilder
     */
    public function __construct(PropertyBuilder $propertyBuilder)
    {
        $this->propertyBuilder = $propertyBuilder;
    }
}