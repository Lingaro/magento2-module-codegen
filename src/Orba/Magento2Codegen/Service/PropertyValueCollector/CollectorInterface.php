<?php

namespace Orba\Magento2Codegen\Service\PropertyValueCollector;

use Orba\Magento2Codegen\Model\PropertyInterface;

interface CollectorInterface
{
    /**
     * @param PropertyInterface $property
     * @return mixed
     */
    public function collectValue(PropertyInterface $property);
}