<?php

/**
 * @copyright Copyright © 2021 Orba. All rights reserved.
 * @author    info@orba.co
 */

declare(strict_types=1);

namespace Orba\Magento2Codegen\Service\Magento;

use Orba\Magento2Codegen\Util\Magento\Config\Dom;

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
