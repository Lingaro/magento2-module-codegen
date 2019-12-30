<?php

namespace Orba\Magento2Codegen\Service;

use Orba\Magento2Codegen\Util\TemplatePropertyBag;

interface TemplateProcessorInterface
{
    public function getPropertiesInText(string $text): array;

    public function replacePropertiesInText(string $text, TemplatePropertyBag $properties): string;
}