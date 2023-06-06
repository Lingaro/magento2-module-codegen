<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Lingaro\Magento2Codegen\Service\FileMerger\PhpMerger\NodeTree\NodeCleaner;

use Lingaro\Magento2Codegen\Service\FileMerger\PhpMerger\NodeTree\NameResolver\NodeChainNameResolver;
use PhpParser\Node\Stmt\Declare_;

class DeclareCleaner implements NodeCleanerInterface
{
    private NodeChainNameResolver $nameResolver;

    public function __construct(NodeChainNameResolver $nameResolver)
    {
        $this->nameResolver = $nameResolver;
    }

    public function getCode(): string
    {
        return Declare_::class;
    }

    public function clean(array $nodes): array
    {
        $result = [];
        foreach ($nodes as $node) {
            $name = $this->nameResolver->resolve($node);
            $result[$name] = $node;
        }
        return $result;
    }
}
