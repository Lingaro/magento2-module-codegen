<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Orba\Magento2Codegen\Service\FileMerger;

use InvalidArgumentException;
use Orba\Magento2Codegen\Service\JsConverter;

use function rtrim;
use function strlen;
use function strpos;
use function substr;

class RequirejsConfigMerger extends AbstractMerger implements MergerInterface
{
    private const VAR_CONFIG_STRING = 'var config = ';

    private JsonMerger $jsonMerger;
    private JsConverter $jsConverter;

    public function __construct(JsonMerger $jsonMerger, JsConverter $jsConverter)
    {
        $this->jsonMerger = $jsonMerger;
        $this->jsConverter = $jsConverter;
    }

    public function merge(string $oldContent, string $newContent): string
    {
        $oldJson = $this->getJsObjectString($oldContent);
        $newJson = $this->getJsObjectString($newContent);
        return self::VAR_CONFIG_STRING . $this->jsonMerger
            ->merge($oldJson, $newJson, JSON_PRETTY_PRINT + JSON_FORCE_OBJECT + JSON_UNESCAPED_SLASHES);
    }

    private function getJsObjectString(string $content): string
    {
        $pos = strpos($content, self::VAR_CONFIG_STRING);
        if ($pos === false) {
            throw new InvalidArgumentException('Merged string is not a requirejs-config.js file content.');
        }
        return $this->jsConverter->convertToJson(
            rtrim(substr($content, $pos + strlen(self::VAR_CONFIG_STRING)), " \t\n\r\0\x0B;")
        );
    }
}
