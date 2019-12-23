<?php

namespace Orba\Magento2Codegen\Service;

use Orba\Magento2Codegen\Util\TemplatePropertyBag;

class TemplatePropertyUtil
{
    /**
     * Regex to identify properties ${} in text
     */
    const PROPERTY_REGEX = '/\$\{([^\$}]+)\}/';

    public function getPropertiesInText(string $text): array
    {
        preg_match_all(self::PROPERTY_REGEX, $text, $matches);
        return array_unique($matches[1]);
    }

    public function replacePropertiesInText(string $text, TemplatePropertyBag $properties): string
    {
        $iteration = 0;
        while (strpos($text, '${') !== false) {
            $text = preg_replace_callback(
                self::PROPERTY_REGEX,
                function ($matches) use ($properties) {
                    $propertyName = $matches[1];
                    if (!isset($properties[$propertyName])) {
                        return $matches[0];
                    }
                    return $properties[$propertyName];
                },
                $text
            );

            // keep track of iterations so we can break out of otherwise infinite loops.
            $iteration++;
            if ($iteration === 5) {
                return $text;
            }
        }
        return $text;
    }
}