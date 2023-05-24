<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Orba\Magento2Codegen\Service\StringFilter;

use function str_replace;

class KebabCaseFilter extends SnakeCaseFilter
{
    public function filter(string $text): string
    {
        return str_replace('_', '-', parent::filter($text));
    }
}
