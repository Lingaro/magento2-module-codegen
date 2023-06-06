<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Lingaro\Magento2Codegen\Service;

use OviDigital\JsObjectToJson\JsConverter as OviJsConverter;

use function str_replace;

class JsConverter
{
    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function convertToJson(string $jsObject): string
    {
        $convertedString = OviJsConverter::convertToJson($jsObject);
        // Hacky workaround for "convertToJson" method delimiting true, false and null values with double quotes.
        // It's not perfect, but please don't bite ;-)
        return str_replace([':"true"', ':"false"', ':"null"'], [':true', ':false', ':null'], $convertedString);
    }
}
