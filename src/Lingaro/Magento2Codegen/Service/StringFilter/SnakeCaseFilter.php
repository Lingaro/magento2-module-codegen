<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Lingaro\Magento2Codegen\Service\StringFilter;

use function preg_replace;
use function strtolower;

class SnakeCaseFilter implements FilterInterface
{
    public function filter(string $text): string
    {
        $text = preg_replace('/([a-z0-9])([A-Z])/', '$1_$2', $text);
        $text = preg_replace('/([A-Z])([A-Z][a-z0-9])/', '$1_$2', $text);
        $text = preg_replace('/[^a-zA-Z0-9]/', '_', $text);
        $text = preg_replace('/_+/', '_', $text);
        $text = strtolower($text);
        return trim($text, '_');
    }
}
