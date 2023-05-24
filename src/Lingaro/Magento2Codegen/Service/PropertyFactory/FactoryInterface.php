<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Lingaro\Magento2Codegen\Service\PropertyFactory;

use Lingaro\Magento2Codegen\Model\PropertyInterface;

interface FactoryInterface
{
    public function create(string $name, array $config): PropertyInterface;
}
