<?php

namespace Orba\Magento2Codegen\Service\StringFilter;

class CamelCaseFilter implements FilterInterface
{
    public function filter(string $text): string
    {
        $text = preg_replace('/[^a-zA-Z0-9]/', ' ', $text);
        $text = preg_replace('/([a-z0-9])([A-Z])/', '$1 $2', $text);
        $text = preg_replace('/([A-Z])([A-Z][a-z0-9])/', '$1 $2', $text);
        $text = ucwords(strtolower($text));
        $text = str_replace(' ', '', $text);
        $text = lcfirst($text);
        return $text;
    }
}
