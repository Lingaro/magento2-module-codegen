<?php

namespace Orba\Magento2Codegen\Service\StringFilter;

use ICanBoogie\Inflector;

/**
 * Transforms words from singular to plural
 * Class PluralizeFilter
 * @package Orba\Magento2Codegen\Service\StringFilter
 */
class PluralizeFilter implements FilterInterface
{
    public function filter(string $text): string
    {
        $inflector = Inflector::get(Inflector::DEFAULT_LOCALE);
        $text = $inflector->pluralize($text);
        return $text;
    }
}
