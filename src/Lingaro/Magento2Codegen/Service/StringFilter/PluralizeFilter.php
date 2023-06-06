<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Lingaro\Magento2Codegen\Service\StringFilter;

use ICanBoogie\Inflector;

/**
 * Transforms words from singular to plural
 */
class PluralizeFilter implements FilterInterface
{
    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function filter(string $text): string
    {
        $inflector = Inflector::get(Inflector::DEFAULT_LOCALE);
        return $inflector->pluralize($text);
    }
}
