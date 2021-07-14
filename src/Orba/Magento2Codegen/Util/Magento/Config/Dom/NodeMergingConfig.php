<?php

/**
 * @copyright Copyright © 2021 Orba. All rights reserved.
 * @author    info@orba.co
 */

declare(strict_types=1);

namespace Orba\Magento2Codegen\Util\Magento\Config\Dom;

/**
 * Configuration of identifier attributes to be taken into account during merging
 */
class NodeMergingConfig
{
    private NodePathMatcher $nodePathMatcher;

    /**
     * Format: array('/node/path' => '<node_id_attribute>', ...)
     */
    private array $idAttributes;

    public function __construct(NodePathMatcher $nodePathMatcher, array $idAttributes)
    {
        $this->nodePathMatcher = $nodePathMatcher;
        $this->idAttributes = $idAttributes;
    }

    /**
     * Retrieve name of an identifier attribute for a node
     */
    public function getIdAttribute(string $nodeXpath): ?string
    {
        foreach ($this->idAttributes as $pathPattern => $idAttribute) {
            if ($this->nodePathMatcher->match($pathPattern, $nodeXpath)) {
                return $idAttribute;
            }
        }
        return null;
    }
}
