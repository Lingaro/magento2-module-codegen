<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Lingaro\Magento2Codegen\Service\StringFilter;

use function preg_replace;
use function str_replace;
use function strtolower;
use function ucfirst;

/**
 * First letter capital, rest lower case, no underscore, for directory for controller
 */
class CapitalCaseFilter implements FilterInterface
{
    public function filter(string $text): string
    {
        $text = preg_replace('/[^a-zA-Z0-9]/', ' ', $text);
        $text = preg_replace('/([a-z0-9])([A-Z])/', '$1 $2', $text);
        $text = strtolower($text);
        $text = str_replace(' ', '', $text);
        return ucfirst($text);
    }
}
