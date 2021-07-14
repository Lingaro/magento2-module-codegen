<?php

/**
 * @copyright Copyright © 2021 Orba. All rights reserved.
 * @author    info@orba.co
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
