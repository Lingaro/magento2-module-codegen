<?php

namespace Orba\Magento2Codegen\Service\StringFilter;

/**
 * First letter capital, rest lower case, no underscore, for directory for controller
 * Class CapitalCaseFilter
 * @package Orba\Magento2Codegen\Service\StringFilter
 */
class CapitalCaseFilter implements FilterInterface
{
    public function filter(string $text): string
    {
        $text = preg_replace('/[^a-zA-Z0-9]/', ' ', $text);
        $text = preg_replace('/([a-z0-9])([A-Z])/', '$1 $2', $text);
        $text = strtolower($text);
        $text = str_replace(' ', '', $text);
        $text = ucfirst($text);
        return $text;
    }
}
