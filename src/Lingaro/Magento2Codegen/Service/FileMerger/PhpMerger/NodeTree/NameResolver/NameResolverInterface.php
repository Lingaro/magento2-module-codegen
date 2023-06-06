<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Lingaro\Magento2Codegen\Service\FileMerger\PhpMerger\NodeTree\NameResolver;

use PhpParser\Node;

interface NameResolverInterface
{
    /**
     * Returns full node name
     */
    public function resolve(Node $node): string;
}
