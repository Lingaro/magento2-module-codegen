<?php

/**
 * @copyright Copyright © 2021 Orba. All rights reserved.
 * @author    info@orba.co
 */

declare(strict_types=1);

namespace Orba\Magento2Codegen\Service;

use Symfony\Component\Finder\Finder;

class FinderFactory
{
    public function create(): Finder
    {
        return new Finder();
    }
}
