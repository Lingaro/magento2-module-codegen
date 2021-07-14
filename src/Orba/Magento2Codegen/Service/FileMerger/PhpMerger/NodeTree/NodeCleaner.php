<?php

/**
 * @copyright Copyright © 2021 Orba. All rights reserved.
 * @author    info@orba.co
 */

declare(strict_types=1);

namespace Orba\Magento2Codegen\Service\FileMerger\PhpMerger\NodeTree;

use Orba\Magento2Codegen\Service\FileMerger\PhpMerger\NodeTree\NodeCleaner\ClassMethodNodeCleaner;
use Orba\Magento2Codegen\Service\FileMerger\PhpMerger\NodeTree\NodeCleaner\DeclareCleaner;
use Orba\Magento2Codegen\Service\FileMerger\PhpMerger\NodeTree\NodeCleaner\NodeCleanerInterface;
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
