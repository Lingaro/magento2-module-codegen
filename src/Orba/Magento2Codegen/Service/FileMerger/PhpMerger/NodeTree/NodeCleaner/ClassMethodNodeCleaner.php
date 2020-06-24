<?php


namespace Orba\Magento2Codegen\Service\FileMerger\PhpMerger\NodeTree\NodeCleaner;

use Orba\Magento2Codegen\Service\FileMerger\PhpMerger\NodeTree\NameResolver\NodeChainNameResolver;
use PhpParser\Node;
use PhpParser\Node\Stmt\ClassMethod;

/**
 * Class ClassMethodNodeCleaner
 * @package Orba\Magento2Codegen\Service\FileMerger\PhpMerger\NodeTree\Cleaners
 */
class ClassMethodNodeCleaner implements NodeCleanerInterface
{
    /** @var NodeChainNameResolver */
    private $nameResolver;

    /**
     * ClassMethodNodeCleaner constructor.
     * @param NodeChainNameResolver $nameResolver
     */
    public function __construct(NodeChainNameResolver $nameResolver)
    {
        $this->nameResolver = $nameResolver;
    }

    /**
     * @inheritDoc
     */
    public function getCode(): string
    {
        return ClassMethod::class;
    }

    /**
     * @inheritDoc
     */
    public function clean(array $nodes): array
    {
        $result = [];
        foreach ($nodes as $key => $node) {
            $name = $this->nameResolver->resolve($node);
            $result[$name] = $node;
        }
        return $result;
    }
}