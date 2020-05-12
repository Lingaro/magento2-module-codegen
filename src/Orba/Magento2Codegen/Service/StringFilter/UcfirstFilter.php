<?php

namespace Orba\Magento2Codegen\Service\StringFilter;

/**
 * First letter capital, rest as is
 * Class UcfirstFilter
 * @package Orba\Magento2Codegen\Service\StringFilter
 */
class UcfirstFilter implements FilterInterface
{
    public function filter(string $text): string
    {
        $text = ucfirst($text);
        return $text;
    }
}
