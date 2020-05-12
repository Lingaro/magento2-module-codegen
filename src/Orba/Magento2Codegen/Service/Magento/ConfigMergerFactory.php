<?php

namespace Orba\Magento2Codegen\Service\Magento;

use Orba\Magento2Codegen\Util\Magento\Config\Dom;

class ConfigMergerFactory
{
    public function create(
        string $initialContent,
        array $idAttributes = [],
        string $typeAttributeName = null,
        string $schemaFile = null
    ): Dom
    {
        return new Dom($initialContent, $idAttributes, $typeAttributeName, $schemaFile);
    }
}