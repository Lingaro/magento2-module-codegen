<?php

namespace Orba\Magento2Codegen\Service;

use Orba\Magento2Codegen\Util\TemplatePropertyBag;

class TemplatePropertyBagFactory
{
    public function create(): TemplatePropertyBag
    {
        return new TemplatePropertyBag();
    }
}