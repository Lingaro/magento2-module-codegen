<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Lingaro\Magento2Codegen\Service;

use Symfony\Component\Finder\Finder;

class FinderFactory
{
    public function create(): Finder
    {
        return new Finder();
    }
}
