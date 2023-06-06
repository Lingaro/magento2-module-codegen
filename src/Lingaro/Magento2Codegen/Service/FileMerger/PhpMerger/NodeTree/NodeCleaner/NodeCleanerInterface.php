<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Lingaro\Magento2Codegen\Service\FileMerger\PhpMerger\NodeTree\NodeCleaner;

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
