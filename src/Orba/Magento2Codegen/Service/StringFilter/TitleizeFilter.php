<?php

declare(strict_types=1);

namespace Orba\Magento2Codegen\Service\StringFilter;

use ICanBoogie\Inflector;

/**
 * Capitalizes all the words and replaces some characters in the string to create a nicer looking title
 * Class TitleizeFilter
 * @package Orba\Magento2Codegen\Service\StringFilter
 */
class TitleizeFilter implements FilterInterface
{
    public function filter(string $text): string
    {
        $inflector = Inflector::get(Inflector::DEFAULT_LOCALE);
        $text = $inflector->titleize($text);
        return $text;
    }
}
