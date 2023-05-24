<?php

/**
 * Copyright © 2023 Lingaro sp. z o.o. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace Orba\Magento2Codegen\Service\FileMerger\PhpMerger;

use Exception;
use Orba\Magento2Codegen\Service\FileMerger\PhpMerger\NodeTree\Root;
use Orba\Magento2Codegen\Service\FileMerger\PhpMerger\NodeTree\RootFactory;
use Orba\Magento2Codegen\Service\FileMerger\PhpParser\NodeWrapper;
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Namespace_;

use function count;
use function get_class;
use function is_array;
use function spl_object_hash;

class NodeTree
{
    private const EXPECTED_BRANCHES = [Namespace_::class, Class_::class];

    private Root $root;
    private array $roots = [];

    public function __construct(RootFactory $rootFactory)
    {
        $this->root = $rootFactory->create('root');
    }

    public function grow(NodeWrapper $wrapper): self
    {
        if (!$wrapper->isWrapped()) {
            throw new Exception("Wrapper has no wrapped nodes");
        }

        foreach ($wrapper->getSubNodeNames() as $propertyName) {
            $this->growInternal($this->root, $wrapper, $propertyName);
        }

        return $this;
    }

    public function resolve(): ?self
    {
        if ($this->canResolve()) {
            $this->root->resolve();
            return $this;
        }
        return null;
    }

    private function growInternal(Root $root, Node $node, string $parentParamName): self
    {
        $root = $root->handleNode($node, $parentParamName);

        foreach ($node->getSubNodeNames() as $propertyName) {
            if (is_array($node->$propertyName)) {
                foreach ($node->$propertyName as $subNode) {
                    if ($subNode instanceof Node) {
                        $this->growInternal($root, $subNode, $propertyName);
                    }
                }
            }
        };

        $this->collectRoot($root);
        return $this;
    }

    private function canResolve(): bool
    {
        $gathered = $this->getRoots();
        if (empty($gathered)) {
            throw new Exception("No tree gathered roots");
        }

        foreach (self::EXPECTED_BRANCHES as $class) {
            $classes = $this->getRoots($class);
            if (count($classes) === 1) {
                throw new Exception("Too few expected nodes");
            }
            $prev = null;
            foreach ($classes as $one) {
                if (!$prev) {
                    $prev = $one;
                    continue;
                }
                if ($prev !== $one) {
                    throw new Exception("Mismatched Node names");
                }
            }
        }

        return true;
    }

    private function collectRoot(Root $root): self
    {
        $hash = $this->rootHash();
        $class = get_class($root->getLastNode());
        $this->roots[$hash][$class][] = $root->getFullPath();
        return $this;
    }

    private function getRoots(?string $class = null): ?array
    {
        $hash = $this->rootHash();
        if ($class) {
            return $this->roots[$hash][$class];
        }
        return $this->roots[$hash];
    }

    private function rootHash(): string
    {
        return spl_object_hash($this->root);
    }
}
