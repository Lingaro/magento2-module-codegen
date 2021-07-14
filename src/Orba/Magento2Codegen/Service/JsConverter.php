<?php

/**
 * @copyright Copyright © 2021 Orba. All rights reserved.
 * @author    info@orba.co
 */

declare(strict_types=1);

namespace Orba\Magento2Codegen\Service;

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
