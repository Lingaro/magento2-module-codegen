<?php


namespace Orba\Magento2Codegen\Service\FileMerger\PhpMerger\NodeTree;

use Orba\Magento2Codegen\Service\FileMerger\PhpMerger\NodeTree\NodeCleaner\ClassMethodNodeCleaner;
use Orba\Magento2Codegen\Service\FileMerger\PhpMerger\NodeTree\NodeCleaner\NodeCleanerInterface;
use PhpParser\Node;

/**
 * Class NodeCleaner
 * @package Orba\Magento2Codegen\Service\FileMerger\PhpMerger\NodeTree
 */
class NodeCleaner
{
    /**
     * @var array|NodeCleanerInterface[]
     */
    private $cleaners = [];

    /**
     * NodeCleaner constructor.
     * @param ClassMethodNodeCleaner $classMethodCleaner
     */
    public function __construct(
        ClassMethodNodeCleaner $classMethodCleaner
    ) {
        $this->cleaners = [
            $classMethodCleaner->getCode() => $classMethodCleaner
        ];
    }

    /**
     * @param Node $node
     * @param array|Node[] $nodes
     * @return array
     */
    public function clean(Node $node, array $nodes): array
    {
        if ($cleaner = $this->getCleaner($node)) {
            $nodes = $cleaner->clean($nodes);
        }
        return $nodes;
    }

    /**
     * @param Node $node
     * @return NodeCleanerInterface|null
     */
    private function getCleaner(Node $node): ?NodeCleanerInterface
    {
        $class = get_class($node);
        return isset($this->cleaners[$class]) ? $this->cleaners[$class] : null;
    }
}