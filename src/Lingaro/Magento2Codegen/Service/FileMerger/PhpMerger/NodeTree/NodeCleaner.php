<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Lingaro\Magento2Codegen\Service\FileMerger\PhpMerger\NodeTree;

use Lingaro\Magento2Codegen\Service\FileMerger\PhpMerger\NodeTree\NodeCleaner\ClassMethodNodeCleaner;
use Lingaro\Magento2Codegen\Service\FileMerger\PhpMerger\NodeTree\NodeCleaner\DeclareCleaner;
use Lingaro\Magento2Codegen\Service\FileMerger\PhpMerger\NodeTree\NodeCleaner\NodeCleanerInterface;
use PhpParser\Node;

class NodeCleaner
{
    /**
     * @var NodeCleanerInterface[]
     */
    private array $cleaners;

    public function __construct(ClassMethodNodeCleaner $classMethodCleaner, DeclareCleaner $declareCleaner)
    {
        $this->cleaners = [
            $classMethodCleaner->getCode() => $classMethodCleaner,
            $declareCleaner->getCode() => $declareCleaner
        ];
    }

    /**
     * @param Node[] $nodes
     */
    public function clean(Node $node, array $nodes): array
    {
        $cleaner = $this->getCleaner($node);
        if ($cleaner) {
            $nodes = $cleaner->clean($nodes);
        }
        return $nodes;
    }

    private function getCleaner(Node $node): ?NodeCleanerInterface
    {
        $class = get_class($node);
        return $this->cleaners[$class] ?? null;
    }
}
