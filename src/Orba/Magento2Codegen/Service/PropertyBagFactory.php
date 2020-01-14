<?php

namespace Orba\Magento2Codegen\Service;

use Orba\Magento2Codegen\Util\PropertyBag;

class PropertyBagFactory
{
    public function create(): PropertyBag
    {
        return new PropertyBag();
    }
}