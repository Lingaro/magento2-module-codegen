<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Lingaro\Magento2Codegen\Service\Magento;

use Lingaro\Magento2Codegen\Util\Magento\Config\Dom;

class ConfigMergerFactory
{
    public function create(
        string $initialContent,
        array $idAttributes = [],
        string $typeAttributeName = null,
        string $schemaFile = null
    ): Dom {
        return new Dom($initialContent, $idAttributes, $typeAttributeName, $schemaFile);
    }
}
