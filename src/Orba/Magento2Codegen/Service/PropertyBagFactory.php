<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Orba\Magento2Codegen\Service;

use Orba\Magento2Codegen\Util\PropertyBag;

class PropertyBagFactory
{
    public function create(): PropertyBag
    {
        return new PropertyBag();
    }
}
