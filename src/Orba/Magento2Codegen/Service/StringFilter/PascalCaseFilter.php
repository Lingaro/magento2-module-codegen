<?php

namespace Orba\Magento2Codegen\Service\StringFilter;

class PascalCaseFilter extends CamelCaseFilter
{
    public function filter(string $text): string
    {
        return ucfirst(parent::filter($text));
    }
}