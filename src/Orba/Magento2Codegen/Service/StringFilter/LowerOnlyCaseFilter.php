<?php

namespace Orba\Magento2Codegen\Service\StringFilter;

/**
 * All letters in lower case, no underscores, no spaces
 * Class LowerOnlyCaseFilter
 * @package Orba\Magento2Codegen\Service\StringFilter
 */
class LowerOnlyCaseFilter implements FilterInterface
{
    public function filter(string $text): string
    {
        $text = preg_replace('/[^a-zA-Z0-9]/', ' ', $text);
        $text = strtolower($text);
        $text = str_replace(' ', '', $text);
        return $text;
    }
}
