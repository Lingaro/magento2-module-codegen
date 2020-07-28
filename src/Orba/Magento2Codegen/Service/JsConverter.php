<?php

namespace Orba\Magento2Codegen\Service;

class JsConverter
{
    public function convertToJson(string $jsObject): string
    {
        $convertedString = \OviDigital\JsObjectToJson\JsConverter::convertToJson($jsObject);
        // Hacky workaround for "convertToJson" method delimiting true, false and null values with double quotes.
        // It's not perfect, but please don't bite ;-)
        $convertedString = str_replace([':"true"', ':"false"', ':"null"'], [':true', ':false', ':null'], $convertedString);
        return $convertedString;
    }
}
