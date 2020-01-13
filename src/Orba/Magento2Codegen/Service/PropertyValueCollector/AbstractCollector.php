<?php

namespace Orba\Magento2Codegen\Service\PropertyValueCollector;

use Orba\Magento2Codegen\Model\PropertyInterface;

abstract class AbstractCollector implements CollectorInterface
{
    public function collectValue(PropertyInterface $property)
    {
        $this->validateProperty($property);
        return $this->_collectValue($property);
    }

    protected abstract function validateProperty(PropertyInterface $property): void;

    /**
     * @param PropertyInterface $property
     * @return mixed
     */
    protected abstract function _collectValue(PropertyInterface $property);
}