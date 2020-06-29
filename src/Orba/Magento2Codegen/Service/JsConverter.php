<?php

namespace Orba\Magento2Codegen\Service;

class JsConverter
{
    public function convertToJson(string $jsObject): string
    {
        return \OviDigital\JsObjectToJson\JsConverter::convertToJson($jsObject);
    }
}
