<?php

namespace Orba\Magento2Codegen\Service;

use Orba\Magento2Codegen\Util\PropertyBag;

interface TemplateProcessorInterface
{
    public function replacePropertiesInText(string $text, PropertyBag $properties): string;
}