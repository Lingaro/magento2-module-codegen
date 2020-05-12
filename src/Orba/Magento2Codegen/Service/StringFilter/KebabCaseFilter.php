<?php

namespace Orba\Magento2Codegen\Service\StringFilter;

class KebabCaseFilter extends SnakeCaseFilter
{
    public function filter(string $text): string
    {
        return str_replace('_', '-', parent::filter($text));
    }
}