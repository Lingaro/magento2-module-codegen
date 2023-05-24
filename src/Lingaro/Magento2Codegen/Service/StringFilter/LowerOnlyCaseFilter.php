<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Orba\Magento2Codegen\Service\StringFilter;

use function preg_replace;
use function str_replace;
use function strtolower;

/**
 * All letters in lower case, no underscores, no spaces
 */
class LowerOnlyCaseFilter implements FilterInterface
{
    public function filter(string $text): string
    {
        $text = preg_replace('/[^a-zA-Z0-9]/', ' ', $text);
        $text = strtolower($text);
        return str_replace(' ', '', $text);
    }
}
