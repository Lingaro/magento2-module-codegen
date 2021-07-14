<?php

/**
 * @copyright Copyright © 2021 Orba. All rights reserved.
 * @author    info@orba.co
 */

declare(strict_types=1);

namespace Orba\Magento2Codegen\Service\FileMerger\PhpMerger\NodeTree\NodeCleaner;

use PhpParser\Node;

interface NodeCleanerInterface
{
    /**
     * Returns node class of node that can be handled by this cleaner
     */
    public function getCode(): string;

    /**
     * Accepts and clean similar nodes
     *
     * @param Node[] $nodes
     * @return Node[]
     */
    public function clean(array $nodes): array;
}
