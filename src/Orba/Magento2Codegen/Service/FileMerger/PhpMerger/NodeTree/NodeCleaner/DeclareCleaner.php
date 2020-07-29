<?php

namespace Orba\Magento2Codegen\Service\FileMerger\PhpMerger\NodeTree\NodeCleaner;

use Orba\Magento2Codegen\Service\FileMerger\PhpMerger\NodeTree\NameResolver\NodeChainNameResolver;
use PhpParser\Node\Stmt\Declare_;

/**
 * Class DeclareCleaner
 * @package Orba\Magento2Codegen\Service\FileMerger\PhpMerger\NodeTree\NodeCleaner
 */
class DeclareCleaner implements NodeCleanerInterface
{
    /**
     * @var NodeChainNameResolver
     */
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
        return Declare_::class;
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
