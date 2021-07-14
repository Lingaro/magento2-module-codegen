<?php

/**
 * @copyright Copyright © 2021 Orba. All rights reserved.
 * @author    info@orba.co
 */

declare(strict_types=1);

namespace Orba\Magento2Codegen\Service\StringFilter;

use function ucfirst;

/**
 * First letter capital, rest as is
 */
class UcfirstFilter implements FilterInterface
{
    public function filter(string $text): string
    {
        return ucfirst($text);
    }
}
