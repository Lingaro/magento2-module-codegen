<?php

namespace Orba\Magento2Codegen\Service\Magento;

use Magento\Framework\Config\Dom;
use Orba\Magento2Codegen\Util\Magento\Config\ValidationState;

class ConfigMergerFactory
{
    public function create(
        string $initialContent,
        array $idAttributes = [],
        string $typeAttributeName = null,
        string $schemaFile = null
    ): Dom
    {
        return new Dom($initialContent, new ValidationState(), $idAttributes, $typeAttributeName, $schemaFile);
    }
}